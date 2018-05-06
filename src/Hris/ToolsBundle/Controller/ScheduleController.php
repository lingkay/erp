<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Utility\ManagerGroupName;
use Hris\ToolsBundle\Entity\Schedule;
use Hris\ToolsBundle\Entity\ScheduleEntry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class ScheduleController extends Controller
{
    public function __construct()
    {
        $this->route_prefix = 'hris_tools_schedule';
        $this->title = 'Schedule';
        $this->list_title = 'Schedule';
        $this->list_type = 'static';
        $this->repo = "HrisWorkforceBundle:Attendance";
    }

    public function indexAction($date = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $this->checkAccess($this->route_prefix . '.view');
            $params = $this->getViewParams('List', 'hris_tools_schedule_index');
            $this->padFormParams($params);
            $settings = $this->get('hris_settings');

            if ($date == null) {
                $date = new DateTime();
                $date = $date->format('m-d-Y');
            }
            $dateFMTD = DateTime::createFromFormat('m-d-Y', $date);

            //check if logged-in user is an area manager -- if not disable page
            $user = $this->getUser();
            $params['is_manager'] = false;
            if ($user->getGroup()->getName() == ManagerGroupName::MANAGER_GROUP_NAME) {
                $params['is_manager'] = true;
            } else {
                //return $this->redirect('/');//redirect with warning
            }

            //get locations where user's area == pos loc area
            $params['locations'] = $em->getRepository('GistLocationBundle:POSLocations')->findBy(array('area' => $user->getArea()->getID()));
            $users = $em->getRepository('GistUserBundle:User')->findBy(array('area' => $user->getArea()->getID()));


            //find or create schedule entry
            $schedule = $em->getRepository('HrisToolsBundle:Schedule')->findOneBy(array('area' => $user->getArea()->getID(), 'date' => $dateFMTD));

            if (!$schedule) {
                $schedule = new Schedule();
                $schedule->setArea($user->getArea());
                $schedule->setDate($dateFMTD);
                $em->persist($schedule);
                $em->flush();
            }

            $params['schedule'] = $schedule;


            $user_opts = array();
            foreach ($users as $u) {
                $user_opts[$u->getID()] = $u->getDisplayName();
            }
            $params['user_opts'] = array('0' => '-- Select Employee --') + $user_opts;


            $params['date_to_url'] = $dateFMTD->format("m-d-Y");
            $params['filterDate'] = $dateFMTD->format("m/d/Y");

            $params['employees_data'] = $this->getData($date);

            $params['list_title'] = $this->list_title;
            $params['prefix'] = $this->route_prefix;


            $twig_file = 'HrisToolsBundle:Schedule:index.html.twig';
            return $this->render($twig_file, $params);
        } catch (\Exception $e) {
            //return $this->redirect('/');
            echo $e->getMessage();
        }
    }

    public function getData($date)
    {
        $date = DateTime::createFromFormat('m-d-Y', $date);
        $date->modify('-1 day');
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $allEmployees = $em->getRepository('GistUserBundle:User')->findAll();

        foreach ($allEmployees as $employee) {
            $employeeId = $employee->getID();
            $totalSales = 0;
            $totalCost = 0;
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date->format('Y-m-d'), $date->format('Y-m-d'), null, null);

            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $employeex = $em->getRepository('GistUserBundle:User')->findOneById($transactionItem->getTransaction()->getUserCreate()->getID());
                    if ($employeex->getID() == $employeeId) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }
            }

            $brandTotalProfit = $totalSales - $totalCost;

//            if ($totalSales > 0) {
            $list_opts[] = array(
                'date' => $date->format('Y-m-d'),
                'employee_id' => $employeeId,
                'employee_name' => $employee->getDisplayName(),
                'position' => $employee->getGroup()->getName(),
                'location_yesterday' => '-',
                'total_sales' => number_format($totalSales, 2, '.', ','),
                'total_cost' => number_format($totalCost, 2, '.', ','),
                'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
            );
//            }
        }

        if (count($allEmployees) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }

    public function unassignEmployeeAction($entry_id)
    {
        $em = $this->getDoctrine()->getManager();
        $list_opts = [];
        try {
            $scheduleEntryExists = $em->getRepository('HrisToolsBundle:ScheduleEntry')->findOneBy(array('id' => $entry_id));
            $user_id = $scheduleEntryExists->getEmployee()->getID();
            if ($scheduleEntryExists) {
                $em->remove($scheduleEntryExists);
                $em->flush();
                $list_opts[] = array(
                    'success' => true,
                    'message' => 'Success!',
                    'user_id' => $user_id
                );

                return new JsonResponse($list_opts);
            }

            $list_opts[] = array(
                'success' => false,
                'message' => 'Cannot un-assign employee!'
            );

            return new JsonResponse($list_opts);
        } catch (\Exception $e) {
            $list_opts[] = array(
                'success' => false,
                'message' => 'Cannot un-assign employee!'
            );

            return new JsonResponse($list_opts);
        }
    }

    public function assignEmployeeAction($user_id, $date, $schedule_id, $location_id, $mode = 'assign')
    {
        $em = $this->getDoctrine()->getManager();
        $list_opts = [];
        try {
            $scheduleEntryExists = $em->getRepository('HrisToolsBundle:ScheduleEntry')->findOneBy(array('schedule' => $schedule_id, 'employee' => $user_id));
            if ($scheduleEntryExists) {
                $list_opts[] = array(
                    'success' => false,
                    'message' => 'Employee already assigned!'
                );

                return new JsonResponse($list_opts);
            }

            $schedule = $em->getRepository('HrisToolsBundle:Schedule')->findOneBy(array('id' => $schedule_id));
            $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$user_id));
            $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$location_id));

            if (!$pos_location || !$schedule || !$user) {
                $list_opts[] = array(
                    'success' => false,
                    'message' => 'Cannot process request!'
                );

                return new JsonResponse($list_opts);
            }

            $scheduleEntry = new ScheduleEntry();
            $scheduleEntry->setEmployee($user);
            $scheduleEntry->setSchedule($schedule);
            $scheduleEntry->setPOSLocation($pos_location);
            $em->persist($scheduleEntry);
            $em->flush();

            if (!$scheduleEntry) {
                $list_opts[] = array(
                    'success' => false,
                    'message' => 'Cannot process request!'
                );

                return new JsonResponse($list_opts);
            }

            $list_opts[] = array(
                'success' => true,
                'entry_id' => $scheduleEntry->getID(),
                'message' => 'Request processed!'
            );

            return new JsonResponse($list_opts);
        } catch (\Exception $e) {
            $list_opts = [];
            $list_opts[] = array(
                'success' => false,
                'message' => 'Cannot process request!'
            );

            return new JsonResponse($list_opts);
        }
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new AttendanceReport();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('emp','employee','getEmployee'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date','getDateDisplay','date'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        return $params;
    }
}