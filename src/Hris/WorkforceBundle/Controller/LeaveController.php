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

use Hris\WorkforceBundle\Entity\Leave;
use Hris\WorkforceBundle\Entity\Employee;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Leave\LeaveType;

use DateTime;

class LeaveController extends CrudController
{
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_leave';
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

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $leaves = [];
        if ($object->getID() != null) 
        {
            $params['readonly'] = ($object->getStatus() == 'Pending' ? False : True);
            $leave_types = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->findBy(
                    array('employee' => $object->getEmployee()->getID())
                );
            foreach ($leave_types as $leave) {
                $leaves[$leave->getID()] = $leave->getLeaveType()->getName();
            }
            $params['leave_type'] = $leaves;

            if ($this->getUser()->getEmployee() != null) 
            {
                //check if hr
                $groups = $this->getUser()->getGroups();
                foreach ($groups as $group) 
                {
                    if ($group->getName() == "hr_admin" && $object->getDateReviewedHR() == null) 
                    {
                        $params['hr'] = 'true';
                        break;
                    }
                    else
                    {
                        $params['hr'] = 'false';
                    }
                }
                //check if dept head of employee
                if ($object->getEmployee()->getDepartment()->getDeptHead()->getID() == $this->getUser()->getEmployee()->getID() && $object->getDateReviewedDH() == null) 
                {
                    $params['dh'] = 'true';
                }
                else
                {
                    $params['dh'] = 'false';
                }
                // check if vp_operations
                if ($this->getUser()->getEmployee()->getJobTitle()->getName() == 'VP Operations') {
                    $params['vp'] = 'true';
                } else {
                    $params['vp'] = 'false';
                }
                
            }
            else
            {
                $params['hr'] = 'false';
                $params['dh'] = 'false';
                $params['vp'] = 'false';
            }
        }
        else
        {
            $params['hr'] = 'false';
            $params['dh'] = 'false';
            $params['vp'] = 'false';
        }

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        $emp_leave = $em->getRepository('HrisWorkforceBundle:EmployeeLeaves')->find($data['emp_leave_id']);
        $emp_leave->setPendingCount($data['apply_leave']);
        $em->persist($emp_leave);
        $em->flush();

        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $o->setEmployee($emp)
            ->setEmpLeave($emp_leave)
            ->setAppliedLeaveDays($data['apply_leave']);

        $filed = new DateTime();
        $start = new DateTime($data['date_start']); 
        $end = new DateTime($data['date_end']);
        $count = 0;
        if($o->getEmpLeave()->getApprovedCount() != null)
        {
            $count = $o->getEmpLeave()->getApprovedCount();
        }
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
                case "ReviewDH":
                    $o->setDateReviewedDH(new DateTime());
                    if ($o->getDateReviewedDH() != null && $o->getDateReviewedHR() != null) 
                    {
                        $o->setStatus(Leave::STATUS_REVIEWED);
                    }
                    else
                    {
                        $o->setStatus(Leave::STATUS_PENDING_REVIEW);
                    }
                    break;
                case "ReviewHR":
                    $o->setDateReviewedHR(new DateTime());
                    if ($o->getDateReviewedDH() != null && $o->getDateReviewedHR() != null) 
                    {
                        $o->setStatus(Leave::STATUS_REVIEWED);
                    }
                    else
                    {
                        $o->setStatus(Leave::STATUS_PENDING_REVIEW);
                    }
                    break;
                case "Approve":
                    $o->setDateApproved(new DateTime());
                    $o->setStatus(Leave::STATUS_APPROVED);
                    $o->setApprovedBy($this->getUser()->getName());
                    $pending = $o->getEmpLeave()->getPendingCount();
                    $new_pending = $pending - $o->getAppliedLeaveDays();
                    $available = $o->getEmpLeave()->getAvailLeaves();
                    $new_available = $available - $o->getAppliedLeaveDays();
                    $o->getEmpLeave()->setPendingCount($new_pending)
                        ->setAvailLeaves($new_available);
                    $count = $count + $o->getAppliedLeaveDays();
                    $o->getEmpLeave()->setApprovedCount($count);
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
            $grid->newJoin('e','employee','getEmployee'),
            // $grid->newJoin('l','leave_type','getLeaveType'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'e'),
            $grid->newColumn('Leave Type', 'getEmpLeaveName', 'emp_leave'),
            $grid->newColumn('Leave Start Date', 'getDateStart', 'date_start', 'o', array($this,'formatDate')),
            $grid->newColumn('Leave End Date', 'getDateEnd', 'date_end', 'o', array($this,'formatDate')),
            $grid->newColumn('Status','getStatus','status'),                        
        );
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
        else
        {
            switch ($obj->getStatus()) {
                case Leave::STATUS_REVIEWED:
                    $source = 'Your leave has been reviewed.';
                    $message = 'Your request for '. $obj->getEmpLeaveName() .' from '. $obj->getDateStart()->format('l\, F j Y') .' to '. $obj->getDateEnd()->format('l \, F j Y').' has been reviewed by your Department Head and HR Administrator.';
                    $link = $this->generateUrl('hris_profile_leave_edit_form', array('id'=>$obj->getID()));
                    $this->notify($obj->getEmployee(), $link, $message, $source);

                    $vp = $setting->getEmployeesByJobTitle($conf->get('hris_vp_operations'));
                    $source_vp = 'Employee leave needs your approval.';
                    $message_vp = $obj->getEmployee()->getDisplayName().' filed for a '. $obj->getEmpLeaveName().' from '. $obj->getDateStart()->format('l\, F j Y') .' to '. $obj->getDateEnd()->format('l \, F j Y').'. This needs your approval.';
                    $link_vp = $this->generateUrl('hris_workforce_leave_edit_form',array('id'=>$obj->getID()));
                    if (!empty($vp)) {
                        foreach ($vp as $veep) {
                            $this->notify($veep,$link_vp,$message_vp,$source_vp);
                        }
                    }
                    break;

                case Leave::STATUS_APPROVED:
                    $source = 'Your leave has been approved.';
                    $message = 'Your request for '. $obj->getEmpLeaveName() .' from '. $obj->getDateStart()->format('l\, F j Y') .' to '. $obj->getDateEnd()->format('l \, F j Y').' has been approved by the VP for Operations.';
                    $link = $this->generateUrl('hris_profile_leave_edit_form', array('id'=>$obj->getID()));
                    $this->notify($obj->getEmployee(), $link, $message, $source);
                    break;

                case Leave::STATUS_REJECT:
                    $source = 'Your leave has been rejected.';
                    $message = 'Your request for '. $obj->getEmpLeaveName() .' from '. $obj->getDateStart()->format('l\, F j Y') .' to '. $obj->getDateEnd()->format('l \, F j Y').' has been rejected.';
                    $link = $this->generateUrl('hris_profile_leave_edit_form', array('id'=>$obj->getID()));
                    $this->notify($obj->getEmployee(), $link, $message, $source);
                    break;
            }
        }
    }
}