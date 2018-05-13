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
            if ($user->getGroup()->getName() == ManagerGroupName::MANAGER_GROUP_NAME || $user->getUsername() == 'admin') {
                $params['is_manager'] = true;
            } else {
                $this->addFlash('error', 'You need to be an Area Manager to view and manage schedules!');
                return $this->redirect('/');//redirect with warning
            }

            //get locations where user's area == pos loc area
            $params['locations'] = $em->getRepository('GistLocationBundle:POSLocations')->findBy(array('area' => $user->getArea()->getID()));

            //find or create schedule entry
            $schedule = $em->getRepository('HrisToolsBundle:Schedule')->findOneBy(array('area' => $user->getArea()->getID(), 'date' => $dateFMTD));

            if (!$schedule) {
                $schedule = new Schedule();
                $schedule->setArea($user->getArea());
                $schedule->setDate($dateFMTD);
                $em->persist($schedule);
                $em->flush();
            }

            $existingEmployees = [];

            if (count($schedule->getEntries()) > 0) {
                foreach ($schedule->getEntries() as $ee) {
                    array_push($existingEmployees, $ee->getEmployee()->getID());
                }
            }

            $params['existing_employees'] = $existingEmployees;
            $params['schedule'] = $schedule;
            $users = $em->getRepository('GistUserBundle:User')->findBy(array('area' => $user->getArea()->getID()));
            $user_opts = array();
            foreach ($users as $u) {
                $user_opts[$u->getID()] = $u->getDisplayName();
            }

            // GET users with "other area"
            $schedulesToday = $em->getRepository('HrisToolsBundle:Schedule')->findBy(array('date' => $dateFMTD));

            if ($schedulesToday) {
                foreach ($schedulesToday as $st) {
                    if ($st->getEntries()) {
                        foreach ($st->getEntries() as $entry) {
                            if ($entry->getEmployee()->getArea()->getID() != $this->getUser()->getArea()->getID() && $entry->getType() == 'Other Area') {
                                $user_opts[$entry->getEmployee()->getID()] = $entry->getEmployee()->getDisplayName();
                            }
                        }
                    }
                }
            }

            $params['user_opts'] = array('0' => '-- Select Employee --') + $user_opts;
            $params['date_to_url'] = $dateFMTD->format("m-d-Y");
            $params['filterDate'] = $dateFMTD->format("m/d/Y");
            $params['employees_data'] = $this->getData($date, $dateFMTD);
            $params['list_title'] = $this->list_title;
            $params['prefix'] = $this->route_prefix;
            $twig_file = 'HrisToolsBundle:Schedule:index.html.twig';
            return $this->render($twig_file, $params);
        } catch (\Exception $e) {
            return $this->redirect('/');
        }
    }

    public function getData($dateSrc, $dateFMTD)
    {
        $date = DateTime::createFromFormat('m-d-Y', $dateSrc);
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $allEmployees = $em->getRepository('GistUserBundle:User')->findBy(array('area' => $this->getUser()->getArea()->getID()));

        foreach ($allEmployees as $employee) {
            $employeeId = $employee->getID();
            $totalSales = 0;
            $totalCost = 0;
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date->modify('-1 day')->format('Y-m-d'), $date->modify('-1 day')->format('Y-m-d'), null, null);

            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $employeex = $em->getRepository('GistUserBundle:User')->findOneById($transactionItem->getTransaction()->getUserCreate()->getID());
                    if ($employeex->getID() == $employeeId) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }
            }

            $brandTotalProfit = $totalSales - $totalCost;
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
        }

        // GET users with "other area"
        $date2 = DateTime::createFromFormat('m-d-Y', $dateSrc);
        $schedulesToday = $em->getRepository('HrisToolsBundle:Schedule')->findBy(array('date' => $dateFMTD));
        $entriesToday = 0;
        if ($schedulesToday) {
            foreach ($schedulesToday as $st) {
                $entriesToday += count($st->getEntries());
                if ($st->getEntries()) {
                    foreach ($st->getEntries() as $entry) {
                        if ($entry->getEmployee()->getArea()->getID() != $this->getUser()->getArea()->getID() && $entry->getType() == 'Other Area') {
                            $list_opts[] = array(
                                'date' => $date->format('Y-m-d'),
                                'employee_id' => $entry->getEmployee()->getID(),
                                'employee_name' => $entry->getEmployee()->getDisplayName(),
                                'position' => $entry->getEmployee()->getGroup()->getName(),
                                'location_yesterday' => '-',
                                'total_sales' => number_format($totalSales, 2, '.', ','),
                                'total_cost' => number_format($totalCost, 2, '.', ','),
                                'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
                            );
                        }
                    }
                }
            }
        }

        if (count($allEmployees) > 0 || $entriesToday > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }

    public function getLocationEntriesAction($schedule_id, $location_id)
    {
        $em = $this->getDoctrine()->getManager();
        $scheduleEntryExists = $em->getRepository('HrisToolsBundle:ScheduleEntry')->findBy(array('schedule' => $schedule_id, 'pos_location' => $location_id));
        $list_opts = [];

        foreach ($scheduleEntryExists as $se) {
            $list_opts[] = array(
                'user_id' => $se->getEmployee()->getID(),
                'user_name' => $se->getEmployee()->getDisplayName(),
                'entry_id' => $se->getID()
            );
        }

        return new JsonResponse($list_opts);
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

    public function checkIfUserAlreadyAssignedInOtherArea($user_id, $dateFMTD)
    {
        $em = $this->getDoctrine()->getManager();
        $schedulesToday = $em->getRepository('HrisToolsBundle:Schedule')->findBy(array('date' => $dateFMTD));
        $hits = 0;
        if ($schedulesToday) {
            foreach ($schedulesToday as $st) {
                if ($st->getEntries()) {
                    foreach ($st->getEntries() as $entry) {
                        if ($entry->getEmployee()->getID() == $user_id && $entry->getType() != 'Other Area') {
                            $hits++;
                        }
                    }
                }
            }
        }

        return $hits;
    }

    public function assignEmployeeAction($user_id, $date, $schedule_id, $location_id, $type = ScheduleEntry::TYPE_WORK)
    {
        $dateFMTD = DateTime::createFromFormat('m-d-Y', $date);
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

            if ($user->getArea()->getID() != $this->getUser()->getArea()->getID() && $type == 'Other Area') {
                $list_opts[] = array(
                        'success' => false,
                        'message' => 'Employee already assigned to other area from original area!'
                    );

                    return new JsonResponse($list_opts);
            }

            $hits = $this->checkIfUserAlreadyAssignedInOtherArea($user->getID(), $dateFMTD);

            if ($hits > 0) {
                $list_opts[] = array(
                        'success' => false,
                        'message' => 'Employee already assigned in another area!'
                    );

                    return new JsonResponse($list_opts);
            }

            if ($type == ScheduleEntry::TYPE_WORK) {
                if (!$pos_location || !$schedule || !$user) {
                    $list_opts[] = array(
                        'success' => false,
                        'message' => 'Cannot process request!'
                    );

                    return new JsonResponse($list_opts);
                }
            } else {
                if (!$schedule || !$user) {
                    $list_opts[] = array(
                        'success' => false,
                        'message' => 'Cannot process request!'
                    );

                    return new JsonResponse($list_opts);
                }
            }


            $scheduleEntry = new ScheduleEntry();
            $scheduleEntry->setEmployee($user);
            $scheduleEntry->setSchedule($schedule);
            $scheduleEntry->setPOSLocation($pos_location);
            $scheduleEntry->setType($type);

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
                'message' => 'Cannot assign employee!'
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
