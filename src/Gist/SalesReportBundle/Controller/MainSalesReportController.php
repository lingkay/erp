<?php

namespace Gist\SalesReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class MainSalesReportController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_sales_report_main';
        $this->title = 'Main Sales Report';

        $this->list_title = 'Sales Report';
        $this->list_type = 'static';
        $this->repo = "GistPOSERPBundle:POSTransaction";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_attendance_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'GistSalesReportBundle:MainReport:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['trans_mode_opts'] = array('\'normal\'' => 'Normal', '\'exchange\'' => 'Exchange', '\'refund\'' => 'Refund');
        $params['trans_type_opts'] = array('\'reg\'' => 'Regular', '\'per\'' => 'Per-item Discount', '\'bulk\'' => 'Bulk');
        $params['title_opts'] = null;
        $params['level_opts'] = null;
        $params['status_opts'] = null;
        $params['location_opts'] = null;
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['sched_opts'] = null;
        $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');
        $params['day_opts'] = array('\'Monday\'' => 'Monday', '\'Tuesday\'' => 'Tuesday', '\'Wednesday\''=>'Wednesday', '\'Thursday\''=>'Thursday', '\'Friday\''=>'Friday', '\'Saturday\''=>'Saturday', '\'Sunday\''=>'Sunday');
        return $this->render($twig_file, $params);
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisReportBundle:Attendance:action.html.twig',
            $params
        );
    }

    public function viewAction()
    {
        $this->hookPreAction();
        $gl = $this->setupGridLoader();
        $params = $this->getViewParams('List', 'hris_report_attendance_view');
        $twig_file = 'HrisReportBundle:Attendance:view.html.twig';
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");
        $this->padFormParams($params, $date_from, $date_to);
        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['list_title'] = $this->list_title;
        return $this->render($twig_file, $params);
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
            $grid->newJoin('u','user_create','getUserCreate'),
            $grid->newJoin('c','customer','getCustomer'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
//        return array(
//            $grid->newColumn('Date','getDateDisplay','date'),
//            $grid->newColumn('Employee','getDisplayName','last_name','emp'),
//            $grid->newColumn('Time-In', 'getTimeIn', 'time_in'),
//            $grid->newColumn('Time-Out', 'getTimeOut', 'time_out'),
//            $grid->newColumn('Late', 'getLateDisplay', 'late'),
//            $grid->newColumn('Undertime', 'getUnderTimeDisplay', 'undertime'),
//            $grid->newColumn('Status', 'getStatus', 'status'),
//        );

        return array(
            $grid->newColumn('Transaction #','getTransDisplayId','trans_display_id'),
            $grid->newColumn('Customer','getNameFormatted','last_name','c'),
            $grid->newColumn('Contact #','getTransDisplayId','trans_display_id'),
            $grid->newColumn('Address','getTransDisplayId','trans_display_id'),
            $grid->newColumn('Grand Total','getTransDisplayId','trans_display_id'),
            $grid->newColumn('Created By','getTransDisplayId','trans_display_id'),
            $grid->newColumn('POS Location','getTransDisplayId','trans_display_id'),
            $grid->newColumn('Date Created', 'getDateCreateFormatted', 'date_create'),
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository('GistCustomerBundle:Customer')->findAll();
        $pos_locs = $em->getRepository('GistLocationBundle:POSLocations')->findAll();


        $customer_opts = array();
        foreach ($customers as $c)
        {
            $customer_opts[$c->getID()] = $c->getNameFormatted();
        }
        $params['customer_opts'] = array('0' => '-- Select Customer --') + $customer_opts;

        $location_opts = array();
        foreach ($pos_locs as $p)
        {
            $location_opts[$p->getID()] = $p->getName();
        }
        $params['location_opts'] = array('0' => '-- Select Location --') + $location_opts;


        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {

    }



    //FOR REPORT

    public function gridReportAction($id = null, $department = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterAttendanceGrid2($id,$department,$date_from,$date_to, $position, $rank, $gender, $days, $late, $late_to, $undertime, $undertime_to, $status, $location));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterAttendanceGrid2($id = null, $department = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location)
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $date = new DateTime();

        $date_from = $date_from=='null'? new DateTime($date->format('Ym01')):new DateTime($date_from);
        $date_to = $date_to=='null'? new DateTime($date->format('Ymt')):new DateTime($date_to);

        $qry[] = "(o.id > 0)";

        $qry[] = "(o.date_create >= '".$date_from->format('Y-m-d')." 00:00:00' AND o.date_create <= '".$date_to->format('Y-m-d')." 23:59:59')";

//        if($department != null and $department != 'null')
//        {
//
//            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$department."'))";
//        }
//
        if ($id != null and $id != 'null')
        {
            $qry[] = "(o.trans_display_id = '".$id."')";
        }
//
//        if ($location != null and $location != 'null')
//        {
//            $qry[] = "(emp.location = '".$location."')";
//        }
//
//        if ($gender != 'null')
//        {
//            if ($gender != null)
//            {
//                $qry[] = "(emp.gender IN (".$gender."))";
//            }
//        }
//
//        if ($rank != 'null')
//        {
//            if ($rank != null)
//            {
//                $qry[] = "(emp.job_level = ".$rank.")";
//            }
//        }
//
//        if ($position != 'null')
//        {
//            if ($position != null)
//            {
//                $qry[] = "(emp.job_title = ".$position.")";
//            }
//        }
//
//        if ($status != 'null')
//        {
//            if ($status != null)
//            {
//                $qry[] = "(o.status = ".$status.")";
//            }
//        }
//
//        if ($late != '0')
//        {
//            if ($late != 0)
//            {
//                $qry[] = "(o.late >= ".$late." AND o.late <= ".$late_to.")";
//            }
//        }
//
//        if ($undertime != '0')
//        {
//            if ($undertime != 0)
//            {
//                $qry[] = "(o.undertime >= ".$undertime." AND o.undertime <= ".$undertime_to.")";
//            }
//        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    public function gridAttendanceReportAction($id = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $undertime = null, $status = null)
    {

    }
}