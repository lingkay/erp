<?php

namespace Hris\ProfileBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManager;

use Hris\WorkforceBundle\Entity\Reimbursement;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use Hris\WorkforceBundle\Entity\Leave;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\EmployeeLeaves;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Leave\LeaveType;

use DateTime;

class LeaveController extends CrudController
{
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_profile_leave';
		$this->title = 'Leave';

		$this->list_title = 'Leave';
		$this->list_type = 'dynamic';
		$this->repo = 'HrisWorkforceBundle:Leave';
	}

	protected function getObjectLabel($obj) {
        if ($obj == null)
            return '';
        return $obj->getID();
    }

    protected function newBaseClass() {
        return new Leave();
    }

    public function callbackGrid($id)
    {
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('HrisWorkforceBundle:Leave')->find($id);

        $params = array(
            'id' => $id,
            'status' => $obj->getStatus(),
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisProfileBundle:Leave:action.html.twig',
            $params
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $leaves = [];
        if ($this->getUser()->getEmployee() != null or $this->getUser()->getEmployee() != '') {
            $leave_types = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findBy(
                    array('employee' => $this->getUser()->getEmployee()->getID())
                );

            foreach ($leave_types as $leave) {
                $leaves[$leave->getID()] = $leave->getLeaveType()->getName();
            }
            $params['leave_type'] = $leaves;
        }

        if ($object->getID() != null) {
            $params['readonly'] = ($object->getStatus() == 'Pending' ? False : True);

            $leave_types = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findBy(
                    array('employee' => $object->getEmployee()->getID())
                );
            // $leaves[0] = '-- Select Leave Type --';
            foreach ($leave_types as $leave) {
                $leaves[$leave->getID()] = $leave->getLeaveType()->getName();
            }
            $params['leave_type'] = $leaves;
        }

        $holidays = [];
        $data = $em->getRepository('HrisAdminBundle:Holiday')->findAll();
        foreach ($data as $holiday) {
            $holidays[] = $holiday->getDate()->format('m/d/Y');
        }

        $params['holidays'] = $holidays;

        $params['weekends'] = array(
                1 => "include Saturday/s?",
            );

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
    	// echo "<pre>";
    	// print_r($data);
    	// echo "</pre>";
    	// die();

    	$em = $this->getDoctrine()->getManager();

        $emp_leave = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->find($data['emp_leave_id']);
        $emp_leave->setPendingCount($data['apply_leave']);
        $em->persist($emp_leave);
        $em->flush();

        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
    	$o->setEmployee($emp)
    		->setEmpLeave($emp_leave)
            ->setAppliedLeaveDays($data['apply_leave']);

        if (isset($data['weekends'])) {
            foreach ($data['weekends'] as $id => $selected) {
                $o->setAcceptSat(true);
            }
        } else {
            $o->setAcceptSat(false);
        }

    	$filed = new DateTime();
    	$start = new DateTime($data['date_start']); 
    	$end = new DateTime($data['date_end']);

    	$o->setDateFiled($filed)
    		->setDateStart($start)
    		->setDateEnd($end);

        $o->setNotes($data['notes']);

        if ($is_new) {
            $o->setStatus(Leave::STATUS_PENDING);
        }

        if (isset($data['action_btn'])) {
            switch($data['action_btn'])
            {
                case "Review":
                    $o->setStatus(Leave::STATUS_REVIEWED);
                    break;
                case "Approve":
                    $o->setDateApproved(new DateTime());
                    $o->setStatus(Leave::STATUS_APPROVED);
                    $o->setApprovedBy($this->getUser()->getName());
                    $pending = $o->getEmpLeave()->getPendingCount();
                    $new_pending = $pending - $o->getAppliedLeaveDays();
                    $available = $o->getEmpLeave()->getAvailLeaves();
                    $new_available = $available - $o->getAppliedLeaveDays();
                    if ($o->getEmpLeave()->getLeaveType()->getCountType() == 'per Year') {
                        $o->getEmpLeave()->setPendingCount($new_pending)
                            ->setAvailLeaves($new_available);
                    } else {
                        $o->getEmpLeave()->setPendingCount($new_pending);
                    }
                    $o->getEmpLeave()->setApprovedCount($o->getAppliedLeaveDays());
                break;

                case "Reject":
                    $o->setStatus(Leave::STATUS_REJECT);
                    $pending = $o->getEmpLeave()->getPendingCount();
                    $new_pending = $pending - $o->getAppliedLeaveDays();
                    $o->getEmpLeave()->setPendingCount($new_pending);
                break;
            }
        }

    	$this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array (
            // $grid->newJoin('l','emp_leave','getEmpLeaveName'),
            $grid->newJoin('e','employee','getEmployee'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        if ($this->getUser()->getEmployee() == NULL) {
            return array(
                $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'e'),
                $grid->newColumn('Leave Type', 'getEmpLeaveName', 'emp_leave'),
                $grid->newColumn('Leave Start Date', 'getDateStart', 'date_start', 'o', array($this,'formatDate')),
                $grid->newColumn('Leave End Date', 'getDateEnd', 'date_end', 'o', array($this,'formatDate')),
                $grid->newColumn('Status','getStatus','status'),                        
            );
        }
        else {
            return array(
                $grid->newColumn('Leave Type', 'getEmpLeaveName', 'emp_leave'),
                $grid->newColumn('Leave Start Date', 'getDateStart', 'date_start', 'o', array($this,'formatDate')),
                $grid->newColumn('Leave End Date', 'getDateEnd', 'date_end', 'o', array($this,'formatDate')),
                $grid->newColumn('Status','getStatus','status'),                        
            );
        }
    }

    protected function notify($receipient, $link, $message, $source)
    {
        // $settings = $this->get('hris_settings');

        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> $source,
            'link'=> $link,
            'message'=> $message,
            'type'=> Notification::TYPE_UPDATE,
            'receipient' => $receipient));

        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $setting = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $conf = $this->get('gist_configuration');

        if($is_new)
        {
            $hr = $setting->getDepartment($conf->get('hris_hr_department'));
            $hr_head = $hr->getDeptHead();

            $dept = $setting->getDepartment($obj->getEmployee()->getDepartment()->getID());
            $dept_head = $dept->getDeptHead();

            $message = $obj->getEmployee()->getDisplayName().' filed for a '. $obj->getEmpLeaveName().' from '. $obj->getDateStart()->format('l\, F j Y') .' to '. $obj->getDateEnd()->format('l \, F j Y').'.';
            $source = 'Employee filed a Leave';
            $link = $this->generateUrl('hris_workforce_leave_edit_form',array('id'=>$obj->getID()));

            if ($hr_head != NULL and !empty($hr_head)) {
                $this->notify($hr, $link, $message, $source);
            }

            if ($dept_head != NULL and !empty($dept_head)) {
                $this->notify($dept_head, $link, $message, $source);
            }
        }

        // removed all other notifications since review and approval is on workforce > leave.
        // only notification for new leave application will be created on this side.
        // this is the employee view.
    }

    public function gridAction()
    {
        $gl = $this->setupGridLoader();
        $qry = array();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if ($this->getUser()->getEmployee() == NULL) {
            $gl->setQBFilterGroup($this->filterGrid());
        }
        else {
            $id = $this->getUser()->getEmployee()->getID();
            
            $qry[] = "(o.employee = '".$id."')";

            if (!empty($qry))
            {
                $filter = implode(' AND ', $qry);
                $fg->where($filter);
                $gl->setQBFilterGroup($fg);
            }
        }
        

        $gres = $gl->load();

        
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function ajaxGetLeaveAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        // $query = 'select el from HrisWorkforceBundle:EmployeeLeaves el where el.id = :id';
        // $leave = $em->createQuery($query)
        //             ->setParameter('id', $id)
        //             ->getResult();

        $leave = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->find($id);

        $data = array(
            'id' => $leave->getLeaveType()->getID(),
            'avail_leave' => $leave->getAvailLeaves(),
            'count_type' => $leave->getLeaveType()->getCountType()
            );

        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function ajaxGetEmpLeaveAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $leaves = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findBy(
                array('employee' => $id)
            );

        $list_opts = [];
        foreach($leaves as $leave)
        {
            $list_opts[] = array('id' => $leave->getID(), 'name' => $leave->getLeaveType()->getName());
        }
        return new JsonResponse($list_opts);
    }

    public function deleteAction($id)
    {
        $this->checkAccess($this->route_prefix . '.delete');

        try
        {
            $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();

            $object = $em->getRepository($this->repo)->find($id);

            $pending = $object->getEmpLeave()->getPendingCount();
            $new_pending = $pending - $object->getAppliedLeaveDays();
            $object->getEmpLeave()->setPendingCount($new_pending);

            $em->persist($object);
            $em->flush();

            $odata = $object->toData();
            $this->logDelete($odata);

            $em->remove($object);
            $em->flush();

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' has been deleted.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (DBALException $e)
        {
            // $this->addFlash('error', $e->getMessage());
            
            $this->addFlash('error', 'Could not delete ' . $this->title . '.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }
}