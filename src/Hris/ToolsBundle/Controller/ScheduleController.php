<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
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
        try {
            $this->checkAccess($this->route_prefix . '.view');

            $settings = $this->get('hris_settings');


            $params = $this->getViewParams('List', 'hris_tools_schedule_index');
            $this->padFormParams($params);

            if ($date == null) {
                $date = new DateTime();
                $date = $date->format('m-d-Y');
            }

            $dateFMTD = DateTime::createFromFormat('m-d-Y', $date);
            $params['date_to_url'] = $dateFMTD->format("m-d-Y");
            $params['filterDate'] = $dateFMTD->format("m/d/Y");

            $params['employees_data'] = $this->getData($date);

            $params['list_title'] = $this->list_title;
            $params['prefix'] = $this->route_prefix;


            $twig_file = 'HrisToolsBundle:Schedule:index.html.twig';
            return $this->render($twig_file, $params);
        } catch (\Exception $e) {
            return $this->redirect('/');
        } catch (DateT $e) {

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

            if ($totalSales > 0) {
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
        }

        if (count($allEmployees) > 0) {
            return $list_opts;
        } else {
            return null;
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