<?php

namespace Hris\ReportBundle\Controller;

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

class AttendanceReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_attendance';
		$this->title = 'Attendance Report';

		$this->list_title = 'Attendance Report';
		$this->list_type = 'static';
        $this->repo = "HrisWorkforceBundle:Attendance";
	}

    public function indexAction($employee_id = null, $area_id= null, $pos_loc_id= null, $date= null)
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
        $twig_file = 'HrisReportBundle:Attendance:index.html.twig';

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

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();
        $dept_opts = array();
        $emp_opts = array();

        foreach ($employee as $emp)
        {
            $emp_opts[$emp->getID()] = $emp->getDisplayName();
        }

        $params['dept_opts'] = $dept_opts + $settings->getDepartmentOptions();
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


        $params['list_title'] = $this->list_title;
        $params['att_status_opts'] = array('\'Present\'' => 'Present', '\'Absent\'' => 'Absent', '\'Paid Leave\'' => 'Paid Leave', '\'Unpaid Leave\'' => 'Unpaid Leave', '\'Non-Working Holiday\'' => 'Non-Working Holiday', '\'Absent on Non-Working Holiday\'' => 'Absent on Non-Working Holiday', '\'Non-Working Holiday and Rest Day\'' => 'Non-Working Holiday and Rest Day', '\'Holiday\'' => 'Holiday', '\'Holiday and Rest day\'' => 'Holiday and Rest day', '\'Double Holiday\'' => 'Double Holiday', '\'Double Holiday and Rest day\'' => 'Double Holiday and Rest day', '\'Non-Working\'' => 'Non-Working', '\'Halfday\'' => 'Halfday');
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');
        $params['day_opts'] = array('\'Monday\'' => 'Monday', '\'Tuesday\'' => 'Tuesday', '\'Wednesday\''=>'Wednesday', '\'Thursday\''=>'Thursday', '\'Friday\''=>'Friday', '\'Saturday\''=>'Saturday', '\'Sunday\''=>'Sunday');



        return $params;
    }


}
