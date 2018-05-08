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
        $arm = $this->get('attendance_report_manager');
        $gl = $this->setupGridLoader();

        if ($date != null && $date != 'null') {
            $date = DateTime::createFromFormat('m-d-Y', $date);
        } else {
            $date = new DateTime();
        }

        $params = $this->getViewParams('List', 'hris_report_attendance_index');
        $params['employee_id'] = $employee_id;
        $params['area_id'] = $area_id;
        $params['pos_loc_id'] = $pos_loc_id;
        $params['date'] = $date->format('m/d/Y');
        $params['data_att'] = $arm->getData($employee_id, $area_id, $pos_loc_id, $date);
        $this->padFormParams($params);
        $twig_file = 'HrisReportBundle:Attendance:index.html.twig';

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
        $inv = $this->get('gist_inventory');
        $um = $this->get('gist_user');  
        $params['list_title'] = $this->list_title;
        $user_opts = array(0 => 'All');
        $params['user_opts'] = $user_opts + $um->getUserFullNameOptions();
        // $pos_loc_opts = array(0 => 'All');
        // $params['pos_loc_opts'] = $pos_loc_opts + $inv->getPOSLocationTransferOptionsOnly();
        return $params;
    }
}
