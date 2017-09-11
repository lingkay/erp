<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\WorkforceBundle\Entity\Reimbursement;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

// employee/user
use Hris\WorkforceBundle\Entity\Employee;
use Gist\UserBundle\Entity\User;

// payroll entities
use Hris\PayrollBundle\Entity\PayPayroll;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\PayrollBundle\Entity\PayEarningEntry;

// attendance
use Hris\WorkforceBundle\Entity\Attendance;
use Hris\WorkforceBundle\Entity\Appraisal;
use Hris\WorkforceBundle\Entity\Evaluator;

use DateTime;

class Employee201Controller extends CrudController
{
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_201';
		$this->title = 'Employee 201';

		$this->list_title = 'Employee 201';
		$this->list_type = 'dynamic';
        $this->repo = 'HrisWorkforceBundle:Employee';
	}

	protected function getObjectLabel($obj) {
        if ($obj == null)
            return '';
        return $obj->getID();
    }

    protected function newBaseClass() {
        // return new Leave();
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_view' => 'hris_workforce_201_view_form',
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisWorkforceBundle:Employee201:action.html.twig',
            $params
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();

        $params['files_opts'] = array(
            // 'education' => 'Transcript/Diploma',
            'evaluation' => 'Appraisals/Evaluations',
            // 'clearance' => 'Clearance',
            // 'memo' => 'Memos',
            // 'application' => 'Application Requirements',
            'attendance' => 'Attendance Record'
        );

        $params['pay_opts'] = array(
            'sss' => 'SSS Contribution',
            'philhealth' => 'PhilHealth Contribution',
            // 'pagibig' => 'Pag-ibig Contribution',
            'payroll' => 'Payroll History'
        );

        return $params;
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisWorkforceBundle:Employee201:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    // protected function update($o, $data, $is_new = false)
    // {
    // 	echo "<pre>";
    // 	print_r($data);
    // 	echo "</pre>";
    // 	die();

    //     $em = $this->getDoctrine()->getManager();

    //     $this->updateTrackCreate($o, $data, $is_new);
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name'),
            $grid->newColumn('Job Title', 'getName', 'name','j'),
            $grid->newColumn('Employment Status', 'getEmploymentStatus','employment_status'),
            $grid->newColumn('Department', 'getName', 'name','d'),
        );
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('j', 'job_title', 'getJobTitle'),
            $grid->newJoin('d', 'department', 'getDepartment'),
        );
    }

    public function print201Action($id)
    {
        $data = $this->getRequest()->request->all();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();
        $conf = $this->get('gist_configuration');

        // get employee details
        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($id);
        $params['employee'] = $emp;

        // get payroll history
        $qb->select('pay')
            ->from('HrisPayrollBundle:PayPayroll', 'pay')
            ->join('HrisPayrollBundle:PayPayrollPeriod', 'per', 'WITH', 'pay.payroll_period = per.id')
            ->where("pay.employee = '".$id."'")
            ->orderBy('per.start_date', 'ASC');

        $pay_rec = $qb->getQuery()->getResult();
        $params['payroll'] = $pay_rec;

        $attend = $em->getRepository('HrisWorkforceBundle:Attendance')->findBy(
                array('employee' => $id)
            );
        $params['attendance'] = $attend;
        // $app = $eval->getAppraisal();

        // $params['app'] = $app;

        // $media = $this->get('gist_media');
        // if ($conf->get('hris_com_logo') != '') 
        // {
        //     $path = $media->getUpload($conf->get('hris_com_logo'));

        //     $str = $path->getURL();
        //     $str = parse_url($str, PHP_URL_PATH);
        //     $str = ltrim($str, '/');

        //     $params['logo'] = $str;
        // }
        // else
        // {
        //     $params['logo'] = '';
        // }

        $this->hookPreAction();

        // $config = $this  ->get('gist_configuration');
        if ($conf->get('hris_com_info_company_name') != null) {
            $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        }

        if ($conf->get('hris_com_info_website') != null) {
            $params['company_website'] = $conf->get('hris_com_info_website');
        }

        if ($conf->get('hris_com_info_company_address') != null) {
            $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $twig = 'HrisWorkforceBundle:Employee201:employee-201.html.twig';

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('LETTER');

        // // debug
        // return $this->render($twig, $params);

        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }
}