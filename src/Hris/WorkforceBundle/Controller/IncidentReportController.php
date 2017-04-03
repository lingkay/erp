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

use Hris\WorkforceBundle\Entity\IncidentReport;
use Hris\WorkforceBundle\Entity\Employee;
use Gist\UserBundle\Entity\User;

use DateTime;

class IncidentReportController extends CrudController
{
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_incident';
		$this->title = 'Incident Report';

		$this->list_title = 'Incident Report';
		$this->list_type = 'dynamic';
		// $this->repo = 'HrisWorkforceBundle:IncidentReport';
	}

	protected function getObjectLabel($obj) {
        if ($obj == null)
            return '';
        return $obj->getID();
    }

    protected function newBaseClass() {
        return new IncidentReport();
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this  ->get('gist_configuration');

        $words = explode(" ", $config->get('hris_com_info_company_name'));
        $acronym = "";

        foreach ($words as $w) {
          $acronym .= $w[0];
        }

        $params['company_name_abv'] = $acronym;

        $action = array(
                'Violation' => 'Violation',
                'Disciplinary' => 'Disciplinary Action'
            );

        $departments = $em->getRepository('HrisAdminBundle:Department')->findAll();
        foreach ($departments as $dept) {
            $department[$dept->getID()] = $dept->getName();
        }
        $params['dept_opts'] = $department;

        $locations = $em->getRepository('HrisAdminBundle:Location')->findAll();
        foreach ($locations as $loc) {
            $location[$loc->getID()] = $loc->getName();
        }
        $params['loc_opts'] = $location;

        $params['action'] = $action;
        if ($object->getID() != NULL) {
            if (!$this->getUser()->hasAccess($this->route_prefix . '.review')
                or !$this->getUser()->hasAccess($this->route_prefix . '.add') 
                or !$this->getUser()->hasAccess($this->route_prefix . '.edit')
                or $object->getStatus() == 'Reviewed') {
                $params['readonly'] = true;
            }
        }

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
    	// echo "<pre>";
    	// print_r($data);
    	// echo "</pre>";
    	// die();

        $em = $this->getDoctrine()->getManager();

        // set dates
        $o->setDateHappened(new DateTime($data['doi']))
            ->setDateFiled(new DateTime());

        // set notes
        $o->setNotes($data['desc'])
            ->setConcerns($data['concerns']);

        // set info
        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $o->setEmployee($emp);

        $dept = $em->getRepository('HrisAdminBundle:Department')->find($data['dept']);
        $o->setDepartment($dept);

        $loc = $em->getRepository('HrisAdminBundle:Location')->find($data['loc']);
        $o->setLocation($loc);

        $o->setReporter($this->getUser());

        $o->setProducts($data['prod']);

        if ($data['c_action'] !== 0 or $data['c_action'] !== '') {
            $o->setStatus(IncidentReport::STATUS_REVIEWED);
            $o->setAction($data['c_action']);
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array (
            $grid->newJoin('d','department','getDepartment'),
            $grid->newJoin('e','employee','getEmployee'),
            $grid->newJoin('r','reporter','getReporter'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date of Incident', 'getDateHappened', 'date_happened', 'o', array($this,'formatDate')),
            $grid->newColumn('Involved Department', 'getName', 'name', 'd'),
            $grid->newColumn('Involved Employee', 'getDisplayName', 'last_name', 'e'),
            $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed', 'o', array($this,'formatDate')),
            $grid->newColumn('Reported By', 'getName', 'name', 'r'),
        );
    }
}