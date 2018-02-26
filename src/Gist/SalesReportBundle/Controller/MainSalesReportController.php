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

        $params = $this->getViewParams('List', 'gist_sales_report_main_summary');

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
            $grid->newJoin('pl','pos_location','getPOSLocation'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Transaction #','getTransDisplayId','id'),
            $grid->newColumn('Customer ID','getDisplayID','display_id','c'),
            $grid->newColumn('Customer','getNameFormatted','last_name','c'),
            $grid->newColumn('Contact #','getMobileNumber','mobile_number', 'c'),
            $grid->newColumn('City','getCity','city','c'),
            $grid->newColumn('Total','getTransactionTotal','transaction_total', 'o', array($this,'formatPrice')),
            $grid->newColumn('Created By','getDisplayName','last_name', 'u'),
            $grid->newColumn('POS Location','getName','name','pl'),
            $grid->newColumn('Date Created', 'getDateCreateFormatted', 'date_create'),
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository('GistCustomerBundle:Customer')->findAll();
        $pos_locs = $em->getRepository('GistLocationBundle:POSLocations')->findAll();
        $areas = $em->getRepository('GistLocationBundle:Areas')->findAll();
        $users = $em->getRepository('GistUserBundle:User')->findAll();


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

        $area_opts = array();
        foreach ($areas as $a)
        {
            $area_opts[$a->getID()] = $a->getName();
        }
        $params['area_opts'] = array('0' => '-- Select Area --') + $area_opts;

        $user_opts = array();
        foreach ($users as $u)
        {
            $user_opts[$u->getID()] = $u->getDisplayName();
        }
        $params['user_opts'] = array('0' => '-- Select Area --') + $user_opts;

        $params['trans_mode_opts'] = array('0' => '-- Select Mode --') + array('normal' => 'Normal', 'exchange' => 'Exchange', 'refund' => 'Refund');
        $params['trans_type_opts'] = array('0' => '-- Select Type --') + array('reg' => 'Regular', 'per' => 'Per-item Discount', 'bulk' => 'Bulk');


        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {

    }

    public function gridReportAction($id = null, $date_from = null, $date_to = null, $type = null, $mode = null, $agent = null, $customer = null, $area = null, $pos_loc = null, $customer_id = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterAttendanceGrid2($id, $date_from, $date_to, $type, $mode, $agent, $customer, $area, $pos_loc, $customer_id));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterAttendanceGrid2($id = null, $date_from = null, $date_to = null, $type = null, $mode = null, $agent = null, $customer = null, $area = null, $pos_loc = null, $customer_id = null)
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $date = new DateTime();

        $date_from = $date_from=='null'? new DateTime($date->format('Ym01')):new DateTime($date_from);
        $date_to = $date_to=='null'? new DateTime($date->format('Ymt')):new DateTime($date_to);

        $qry[] = "(o.date_create >= '".$date_from->format('Y-m-d')." 00:00:00' AND o.date_create <= '".$date_to->format('Y-m-d')." 23:59:59')";

        if ($id != null and $id != 'null')
        {
            $qry[] = "(o.trans_display_id LIKE '%".$id."%')";
        }

        if ($pos_loc != null and $pos_loc != 'null')
        {
            $qry[] = "(pl.id = '".$pos_loc."')";
        }

        if ($area != null and $area != 'null')
        {
            $qry[] = "(u.area = '".$area."')";
        }

        if ($type != null and $type != 'null')
        {
            $qry[] = "(o.transaction_type = '".$type."')";
        }

        if ($mode != null and $mode != 'null')
        {
            $qry[] = "(o.transaction_mode = '".$mode."')";
        }

        if ($agent != null and $agent != 'null')
        {
            $qry[] = "(u.id = '".$agent."')";
        }

        if ($customer != null and $customer != 'null')
        {
            $qry[] = "(c.id = '".$customer."')";
        }

        if ($customer_id != null and $customer_id != 'null')
        {
            $qry[] = "(c.display_id LIKE '%".$customer_id."%')";
        }

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