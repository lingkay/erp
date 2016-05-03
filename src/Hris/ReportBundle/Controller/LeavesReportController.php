<?php

namespace Hris\ReportBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Catalyst\ValidationException;
use Hris\WorkforceBundle\Entity\Leave;
use Hris\AdminBundle\Entity\Leave\LeaveType;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class LeavesReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_leave';
		$this->title = 'Leaves Report';

		$this->list_title = 'Leaves Report';
		$this->list_type = 'static';
        $this->repo = 'HrisWorkforceBundle:Leave';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $em = $this->getDoctrine()->getManager();

        $params = $this->getViewParams('List', 'hris_report_leave_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:Leave:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        // FOR REPORT
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');

        $leave = array();
        $leaves = $em->getRepository('HrisAdminBundle:Leave\LeaveType')->findAll();

        foreach ($leaves as $l) {
            $leave[$l->getID()] = $l->getName();
        }
        $params['leave_type'] = $leave;

        $params['leave_status'] = array(
                Leave::STATUS_PENDING => 'Pending',
                Leave::STATUS_APPROVED => 'Approved',
                Leave::STATUS_REJECT => 'Rejected',
                Leave::STATUS_REVIEW => 'Review',
            );

        return $this->render($twig_file, $params);
    }

    public function gridLeaveAction()
    {
        $data = $this->getRequest()->query->all();

        $gl = $this->setupGridLoader();
        $qry = array();

        $grid = $this->get('catalyst_grid');
        $fg = $grid->newFilterGroup();

        if (isset($data['emp_id']) and $data['emp_id'] != NULL) {
            $qry[] = "(o.employee = '".$data['emp_id']."')";
        }

        if (isset($data['department']) and $data['department'] != NULL ) {
            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$data['department']."'))";
        }

        if (isset($data['date_from']) and $data['date_from'] != NULL) {
            $dfrom = $data['date_from']=='null'? new DateTime():new DateTime($data['date_from'].'00:00:00');
            $dto = $data['date_to']=='null'? new DateTime():new DateTime($data['date_to'].'23:59:59');
        
            $qry[] = "(o.date_end <= '".$dto->format('Y-m-d')."' AND o.date_start >= '".$dfrom->format('Y-m-d')."')";
        } 

        if (isset($data['job_title']) and $data['job_title'] != NULL) {
            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.job_title = '".$data['job_title']."'))";
        }

        if (isset($data['job_level']) and $data['job_level'] != NULL) {
            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.job_level = '".$data['job_level']."'))";
        }

        if (isset($data['location']) and $data['location'] != NULL) {
            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.location = '".$data['location']."'))";
        }

        if (isset($data['gender'])) 
        {
            if (!empty($data['gender']) and $data['gender'] != '' and $data['gender'] != null) {
                $gender = implode(',', $data['gender']);
                $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.gender IN (".$gender.")))";
            }
        }

        if (isset($data['leave_type']) and $data['leave_type'] != NULL) {
            $qry[] = "(leave.leave_type = '".$data['leave_type']."')";
        }

        if (isset($data['leave_status']) and $data['leave_status'] != NULL) {
            $qry[] = "(o.status = '".$data['leave_status']."')";
        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gl->setQBFilterGroup($fg);
        }
        else
        {
            $gl->setQBFilterGroup($this->filterGrid());
        }
        

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        // // debug
        // $resp = new Response(json_encode($data));
        // $resp->headers->set('Content-Type', 'application/json');

        return $resp;
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
            $grid->newJoin('leave','emp_leave','getEmpLeave'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Employee','getDisplayName','last_name','emp'),
            $grid->newColumn('Leave Type', 'getEmpLeaveName', 'emp_leave'),
            $grid->newColumn('Start Date', 'getDateStart', 'date_start', 'o', array($this,'formatDate')),
            $grid->newColumn('End Date', 'getDateEnd', 'date_end', 'o', array($this,'formatDate')),
            $grid->newColumn('Days Count','getAppliedLeaveDays','applied_leave_days'), 
            $grid->newColumn('Status','getStatus','status'), 
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
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

    }

    public function printLeaveAction()
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


        // filter
        $qry = $em->createQueryBuilder();
        $qry->from('HrisWorkforceBundle:Leave', 'o');
        $qry->join('HrisWorkforceBundle:Employee','e','WITH','o.employee=e.id');
        $qry->join('HrisWorkforceBundle:EmployeeLeaves','l','WITH','o.emp_leave=l.id');

        if (isset($data['emp_id']) and $data['emp_id'] != NULL) {
            $qry->andwhere("o.employee = '".$data['emp_id']."'");
        }

        if (isset($data['department']) and $data['department'] != NULL ) {
            $qry->andwhere("o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$data['department']."')");
        }

        if (isset($data['date_from']) and $data['date_from'] != NULL) {
            $dfrom = $data['date_from']=='null'? new DateTime():new DateTime($data['date_from'].'00:00:00');
            $dto = $data['date_to']=='null'? new DateTime():new DateTime($data['date_to'].'23:59:59');
        
            $qry->andwhere("o.date_end <= '".$dto->format('Y-m-d')."' AND o.date_start >= '".$dfrom->format('Y-m-d')."'");
        } 

        if (isset($data['job_title']) and $data['job_title'] != NULL) {
            $qry->andwhere("o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.job_title = '".$data['job_title']."')");
        }

        if (isset($data['job_level']) and $data['job_level'] != NULL) {
            $qry->andwhere("o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.job_level = '".$data['job_level']."')");
        }

        if (isset($data['location']) and $data['location'] != NULL) {
            $qry->andwhere("o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.location = '".$data['location']."')");
        }

        if (isset($data['gender'])) 
        {
            if (!empty($data['gender']) and $data['gender'] != '' and $data['gender'] != null) {
                $gender = implode(',', $data['gender']);
                $qry->andwhere("o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.gender IN (".$gender."))");
            }
        }

        if (isset($data['leave_type']) and $data['leave_type'] != NULL) {
            $qry->andwhere("leave.leave_type = '".$data['leave_type']."'");
        }

        if (isset($data['leave_status']) and $data['leave_status'] != NULL) {
            $qry->andwhere("o.status = '".$data['leave_status']."'");
        }

        $filtered = $qry->select('o')
                    ->getQuery()
                    ->getResult();

        $params['data'] = $filtered;

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

        $twig = 'HrisReportBundle:Leave:print.html.twig';

        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('LETTER');

        // return $this->render($twig, $params);

        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }
}