<?php

namespace Hris\AdminBundle\Controller\Leave;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\JsonResponse;

use Hris\AdminBundle\Entity\Leave\LeaveRules as Rules;

class LeaveRulesController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_leave_rules';
        $this->title = 'Leave Rules';

        $this->list_title = 'Leave Rules';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisAdminBundle:Leave\LeaveRules';
    }

    protected function newBaseClass()
    {
        return new Rules();
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $wf = $this->get('hris_settings');

        $params['accrued_opts'] = array(0 => 'No', 1=> 'Yes');
        $params['carried_opts'] = array(0 => 'No', 1=> 'Yes');
        $params['payment_type'] = array(
                Rules::PAY_NONE => 'No Payment',
                Rules::PAY_FULL => 'Full Payment',
                Rules::PAY_HALF => 'Half Payment'
            );

        $types = $em->getRepository('HrisAdminBundle:Leave\LeaveType')->findAll();
        foreach ($types as $type) {
            $type_opts[$type->getID()] = $type->getName();
        }
        $params['type_opts'] = $type_opts;

        $depts = $em->getRepository('HrisAdminBundle:Department')->findAll();
        foreach ($depts as $dept) {
            $dept_opts[$dept->getID()] = $dept->getName();
        }
        $params['dept_opts'] = $dept_opts;

        $levels = $em->getRepository('HrisAdminBundle:JobLevel')->findAll();
        foreach ($levels as $level) {
            $level_opts[$level->getID()] = $level->getName();
        }
        $params['level_opts'] = $level_opts;

        $params['gender_opts'] = array(
                Rules::GENDER_MALE => 'Male',
                Rules::GENDER_FEMALE => 'Female'
            );
        $params['emp_status_opts'] = array(
                Rules::EMP_PROBATIONARY => "Probationary",
                Rules::EMP_CONTRACTUAL => "Contractual",
                Rules::EMP_REGULAR => "Regular",
            );
        $params['accrue_rule_opts'] = array(
                Rules::ACCRUE_WEEK => 'Weekly',
                Rules::ACCRUE_MONTH => 'Monthly',
                Rules::ACCRUE_QUARTER => 'Quarterly',
                Rules::ACCRUE_YEAR => 'Yearly',
            );

        $params['count_type'] = array(
                Rules::COUNT_YEAR => 'per Year',
                Rules::COUNT_REQUEST => 'per Request',
            );

        $override = array(
                'Override' => 'Override existing Leave Details',
                'New' => 'Add as New Leave Details',
            );
        $params['override_opts'] = $override;

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $em = $this->getDoctrine()->getManager();

        $params = [];
        $this->padFormParams($params);

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

        $leave_type = $em->getRepository('HrisAdminBundle:Leave\LeaveType')->find($data['type']);
        $o->setLeaveType($leave_type);

        $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $o->setEmployee($emp);

        $dept = $em->getRepository('HrisAdminBundle:Department')->find($data['dept']);
        $o->setDepartment($dept);

        $level = $em->getRepository('HrisAdminBundle:JobLevel')->find($data['job_level']);
        $o->setJobLevel($level);

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

        $o->setNotes($data['desc']);
        $o->setLeaveCount($data['leave_count']);
        $o->setCountType($data['count_type']);
        $o->setServiceMonths($data['service_months']);
        $o->setPaymentType($data['payment_type']);
        $o->setOverride($data['override_setting']);
        

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getLeaveType()->getName();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array (
            $grid->newJoin('l','leave_type','getLeaveType'),
            $grid->newJoin('e','employee','getEmployee','left'),
            $grid->newJoin('d','department','getDepartment'),
            $grid->newJoin('j','job_level','getJobLevel'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Leave Type', 'getName', 'name','l'),
            $grid->newColumn('Leaves Per Year', 'getLeaveCount', 'leave_count'),
            $grid->newColumn('Length of Service', 'getServiceMonths', 'service_months'),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name','e'),
            $grid->newColumn('Department', 'getName', 'name','d'),
            $grid->newColumn('Job Level', 'getName', 'name','j'),
            $grid->newColumn('Gender', 'getGenderList', 'gender', 'o', null, false),
            $grid->newColumn('Employment Status', 'getEmpStatusList', 'emp_status', 'o', null, false),
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

        return $this->render('HrisAdminBundle:Leave\LeaveRules:form.html.twig', $params);
    }

    protected function addError($obj)
    {
        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        $this->padFormParams($params, $obj);
        $params['action'] = 'Add New';

        return $this->render('HrisAdminBundle:Leave\LeaveRules:form.html.twig', $params);
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

        return $this->render('HrisAdminBundle:Leave\LeaveRules:form.html.twig', $params);
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

        return $this->render('HrisAdminBundle:Leave\LeaveRules:form.html.twig', $params);
    }
}
