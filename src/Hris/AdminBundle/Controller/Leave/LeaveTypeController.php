<?php

namespace Hris\AdminBundle\Controller\Leave;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\JsonResponse;

use Hris\AdminBundle\Entity\Leave\LeaveType;
use Hris\AdminBundle\Entity\Leave\LeaveRules as Rules;
use Hris\AdminBundle\Entity\Requirements;
use Hris\HrisWorkforceBundle\Entity\EmployeeLeaves;

class LeaveTypeController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_leave_type';
        $this->title = 'Leave Type';

        $this->list_title = 'Leave Type';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisAdminBundle:Leave\LeaveType';
    }

    protected function newBaseClass()
    {
        return new LeaveType();
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['accrued_opts'] = array(0 => 'No', 1=> 'Yes');
        $params['carried_opts'] = array(0 => 'No', 1=> 'Yes');
        $params['payment_type'] = array(
            "NONE" => 'No Payment',
            "FULL" => 'Full Payment',
            "HALF" => 'Half Payment'
            );
        $params['req_type'] = array(
            Requirements::TYPE_FILE => 'PDF, Word Documents, Spreadsheet, and the likes',
            Requirements::TYPE_IMAGE => 'Scanned Documents, Pictures, Screenshots, and the likes',
            Requirements::TYPE_NUMBER => 'ID Numbers, Account/Clearing Numbers, and the likes',
            Requirements::TYPE_TEXT => 'Text',
            );
        $params['accrue_rule_opts'] = array(
                Rules::ACCRUE_WEEK => 'Weekly',
                Rules::ACCRUE_MONTH => 'Monthly',
                Rules::ACCRUE_QUARTER => 'Quarterly',
                Rules::ACCRUE_YEAR => 'Yearly',
            );
        $params['gender_opts'] = array(
                Rules::GENDER_MALE => 'Male',
                Rules::GENDER_FEMALE => 'Female'
            );
        $params['emp_status_opts'] = array(
                Rules::EMP_PROBATIONARY => "Probationary",
                Rules::EMP_CONTRACTUAL => "Contractual",
                Rules::EMP_REGULAR => "Regular",
            );

        $params['count_type'] = array(
                Rules::COUNT_YEAR => 'per Year',
                Rules::COUNT_REQUEST => 'per Request',
            );

        if ($o->getID() != null) {
            $params['requirements'] = $this->getRequirements($o->getID());
        }

        return $params;
    }

    protected function getRequirements($req_id)
    {
        $em = $this->getDoctrine()->getManager();

        $reqs = $em->getRepository('HrisAdminBundle:Requirements')->findBy(
            array('leave_type' => $req_id)
            );

        return $reqs;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $em = $this->getDoctrine()->getManager();

        $params = [];
        $this->padFormParams($params, $o);

        $gender = array();
        $gender_opts = $params['gender_opts'];
        foreach ($gender_opts as $id => $gen) {
            foreach ($data['gender'] as $entry) {
                if($entry == $id)
                    $gender[$id] = $gen;
            }
        }
        $o->setGender($gender);

        $emp_status = array();
        $emp_status_opts = $params['emp_status_opts'];
        foreach ($emp_status_opts as $id => $gen) {
            foreach ($data['emp_status'] as $entry) {
                if($entry == $id)
                    $emp_status[$id] = $gen;
            }
        }
        $o->setEmpStatus($emp_status);

        $o->setName($data['name']);
        $o->setNotes($data['desc']);
        $o->setLeaveCount($data['leave_count']);
        $o->setCountType($data['count_type']);
        $o->setServiceMonths($data['service_months']);
        $o->setPaymentType($data['payment_type']);

        if ($data['accrued_enabled'] == 1) {
            $o->setAccrueEnabled(true)
                ->setAccrueCount($data['accrue_time'])
                ->setAccrueRule($data['accrue_rule']);

            if ($data['carried_enabled'] == 1) {
                $o->setCarriedEnabled(true)
                    ->setCarryPercentage($data['carry_percentage'])
                    ->setCarryPeriod($data['carry_period']);
            } else {
                $o->setCarriedEnabled(false)
                    ->setCarryPercentage(null)
                    ->setCarryPeriod(null);
            }
        }
        else {
            $o->setAccrueEnabled(false)
                ->setAccrueCount(null)
                ->setAccrueRule(null);
            $o->setCarriedEnabled(false)
                ->setCarryPercentage(null)
                ->setCarryPeriod(null);
        }

        $em->persist($o);
        $em->flush();
        
        $req = new Requirements();
        if (!empty($data['req_name'])) {
            $reqs = $this->getRequirements($o->getID());
            foreach ($reqs as $r) {
                $em->remove($r);
                $em->flush();
            }

            foreach ($data['req_name'] as $id => $r) {
                $req->setName($r)
                    ->setReqType($data['req_type'][$id])
                    ->setLeaveType($o);
                $em->persist($req);
                $em->flush();
            }
        }
        else
        {
            if ($is_new == false) {
                $reqs = $this->getRequirements($o->getID());
                foreach ($reqs as $r) {
                    $em->remove($r);
                    $em->flush();
                }
            }
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Leave Name', 'getName', 'name'),
            $grid->newColumn('Description', 'getNotes', 'notes'),
            $grid->newColumn('Leaves Count', 'getLeaveCount', 'leave_count'),
            $grid->newColumn('Length of Service (in months)', 'getServiceMonths', 'service_months'),
        );
    }

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);
        $params['action'] = 'Add New';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }

    protected function addError($obj)
    {
        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        $this->padFormParams($params, $obj);
        $params['action'] = 'Add New';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }

     public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');
        $this->padFormParams($params, $obj);
        $params['action'] = 'Edit';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }

    protected function editError($obj, $id)
    {
        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);
        $params['action'] = 'Edit';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }
}
