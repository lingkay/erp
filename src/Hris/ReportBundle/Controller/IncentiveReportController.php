<?php

namespace Hris\ReportBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Catalyst\ValidationException;
use Hris\RemunerationBundle\Entity\Loan;
use Hris\RemunerationBundle\Entity\LoanPayment;
use Hris\RemunerationBundle\Entity\Incentive;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class IncentiveReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_incentives';
		$this->title = 'Incentives Report';

		$this->list_title = 'Incentives Report';
		$this->list_type = 'static';
        $this->repo = 'HrisRemunerationBundle:Incentive';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $em = $this->getDoctrine()->getManager();

        $params = $this->getViewParams('List', 'hris_report_incentives_index');

        $this->padFormParams($params);
        $twig_file = 'HrisReportBundle:Incentive:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        // FOR REPORT
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');
        $params['day_opts'] = array('\'Monday\'' => 'Monday', '\'Tuesday\'' => 'Tuesday', '\'Wednesday\''=>'Wednesday', '\'Thursday\''=>'Thursday', '\'Friday\''=>'Friday', '\'Saturday\''=>'Saturday', '\'Sunday\''=>'Sunday');

        $params['incentive_type'] = array(
                'Marketing' => 'Marketing',
                'Operation' => 'Operation',
                'Sales' => 'Sales',
                'Productivity' => 'Productivity',
                'Others' => 'Others'
            );
        $params['incentive_status'] = array(
                Incentive::STATUS_PENDING => 'Pending',
                Incentive::STATUS_APPROVED => 'Approved',
                Incentive::STATUS_REJECT => 'Rejected',
            );
        $params['incentive_opts'] = array(0 => 'No',1 => 'Yes');

        $params['incentives'] = $em->getRepository('HrisRemunerationBundle:Incentive')->findAll();

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
        // return new LoansReport();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newJoin('emp','employee','getEmployee'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Date','getDateFiledDisplay','date_filed'),
            $grid->newColumn('Employee','getDisplayName','last_name','emp'),
            $grid->newColumn('Department','getDepartment',''),
            $grid->newColumn('Loan Type', 'getType', 'type'),
            $grid->newColumn('Cost (PhP)', 'getCost', 'cost'),
            $grid->newColumn('Payment', 'getPaid', 'paid'),
            $grid->newColumn('Balance', 'getBalance', 'balance'),
            $grid->newColumn('Status', 'getStatus', 'status'),
        );
    }

    public function ajaxFilterGetAction()
    {
        $data = $this->getRequest()->query->all();

        $this->hookPreAction();

        $filtered = $this->filterIncentives($data);
        $query = $this->processArray($filtered);

        $resp = new Response(json_encode($query));
        // $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterIncentives($data)
    {
        $em = $this->getDoctrine()->getManager();

        // "emp_id":"1",
        // "emp":"Faranal, Jerome",
        // "department":"2",
        // "job_title":"13",
        // "job_level":"2",
        // "gender":["'Male'","'Female'"],
        // "incentive_type":"Operation",
        // "loan_cost_start":"15",
        // "loan_cost_end":"200",
        // "incentive_status":"Approved",
        // "date_from":"20150801",
        // "date_to":"20150831"

        $date = new DateTime();

        $query = $em->createQueryBuilder();
        $query->from('HrisRemunerationBundle:Incentive', 'o');
        $query->join('HrisWorkforceBundle:Employee','e','WITH','o.employee=e.id');

        //date range filter
        if ($data['date_from'] != null and $data['date_to'] != null) {
            $date_from = new DateTime($data['date_from']);
            $date_to = new DateTime($data['date_to']);
            $query->andwhere('o.date_filed >= :date_from');
            $query->andwhere('o.date_filed <= :date_to');
            $query->setParameter('date_from', $date_from);
            $query->setParameter('date_to', $date_to);
        }

        //employee filter
        if($data['emp_id'] != null and $data['emp_id'] != '')
        {
            $query->andwhere("e.id = '".$data['emp_id']."'");
        }

        //department filter
        if($data['department'] != null and $data['department'] != '')
        {
            $query->andwhere("e.department = '".$data['department']."'");
        }

        //position filter
        if($data['job_title'] != null and $data['job_title'] != '')
        {
            $query->andwhere("e.job_title = '".$data['job_title']."'");
        }

        //rank filter
        if($data['job_level'] != null and $data['job_level'] != '')
        {
            $query->andwhere("e.job_level = '".$data['job_level']."'");
        }

        //gender filter
        if (isset($data['gender'])) 
        {
            if (!empty($data['gender']) and $data['gender'] != '' and $data['gender'] != null) {
                $gender = implode(',', $data['gender']);
                $query->andwhere ("e.gender IN (".$gender.")");
            }
        }

        if ($data['incentive_type'] != '' and $data['incentive_type'] != null)
        {
            $query->andwhere("o.type = '".$data['incentive_type']."'");
        }

        //status filter
        if ($data['incentive_status'] != '' and $data['incentive_status'] != null)
        {
            $query -> andwhere("o.status = '".$data['incentive_status']."'");
        }

        //cost filter
        if ($data['incentive_cost_start'] >= 0) 
        {
            if ($data['incentive_cost_end'] >= $data['incentive_cost_start'] and $data['incentive_cost_end'] != 0) 
            {
                $query->andwhere("o.cost >= ".$data['incentive_cost_start']." AND o.cost <= ".$data['incentive_cost_end']."");  
            }
        }

        $filtered = $query->select('o')
                    ->getQuery()
                    ->getResult();

        return $filtered;
    }

    protected function processArray($filtered)
    {
        $incentive = [];

        foreach ($filtered as $f) {
            $incentive[] = array(
                'code' => $f->getCode(),
                'date_filed' => $f->getDateFiledDisplay(),
                'date_approved' => $f->getDateApprovedDisplay(),
                'employee' => $f->getEmployee()->getDisplayName(),
                'department' => $f->getEmployee()->getDepartment()->getName(),
                'incentive_type' => $f->getType(),
                'incentive_cost' => $f->getCost(),
                'incentive_status' => $f->getStatus(),
            );
        }

        return $incentive;
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();
        $dept_opts = [];
        $emp_opts = array(0 => '[Select Employee]');

        foreach ($employee as $emp)
        {
            $emp_opts[$emp->getID()] = $emp->getDisplayName();
        }

        $params['dept_opts'] = $settings->getDepartmentOptions();
        $int_opts = [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
        $params['interval'] = 'daily';
        $params['dept_id'] = 0;
        $params['emp_id'] = 0;
        $params['emp_opts'] = $emp_opts;
        $params['int_opts'] = $int_opts;


        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {
        //check for tardiness >= 5 for the current month
        

    }

    public function printAction()
    {
        $data = $this->getRequest()->request->all();

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();

        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }

        $this->hookPreAction();

        $date_from = $data['date_from'] == 'null' ? new DateTime($date->format('Ym01')):new DateTime($data['date_from']);
        $date_to = $data['date_to'] == 'null' ? new DateTime($date->format('Ymt')):new DateTime($data['date_to']);

        $params['date_from_display'] = $date_from;
        $params['date_to_display'] = $date_to;

        $config = $this  ->get('catalyst_configuration');
        if ($conf->get('hris_com_info_company_name') != null) 
        {
            $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        }

        if ($conf->get('hris_com_info_website') != null) 
        {
            $params['company_website'] = $conf->get('hris_com_info_website');
        }

        if ($conf->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('CatalystContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $twig = 'HrisReportBundle:Incentive:print.html.twig';

        $params['data'] = $this->filterIncentives($data);
        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }
}