<?php

namespace Hris\PayrollBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;

use Hris\PayrollBundle\Entity\PayPayroll;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\PayrollBundle\Entity\PayPayrollPeriod;
use Hris\PayrollBundle\Entity\PayEarningEntry;

use Hris\PayrollBundle\Entity\PayTax;

use DateTime;

class ComputationController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_computation';
        $this->title = 'Payroll';

        $this->list_title = 'Payroll';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisPayrollBundle:Computation:index.html.twig';
        
        $this->padFormParams($params);
        
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['title'] = $this->title;
        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['sched_opts'] = $settings->getSchedulesOptions();
        return $this->render($twig_file, $params);      
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        return new PayPayroll();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Name', '', ''),
            $grid->newColumn('Department', '', ''),
            $grid->newColumn('Payroll Schedule', '', ''),
            $grid->newColumn('Remarks', '', ''),            
        );
    }

    public function updateDataAction($id = null, $date_to = null, $date_from = null)
    {
        $dfrom = new DateTime($date_from.('T00:00:00'));
        $dto = new DateTime($date_to.('T23:59:59'));

        $payroll = $this->get('hris_payroll');
        $employee = $this->get('hris_workforce');

        $emp = $employee->getEmployee($id);
        $salary = $emp->getPayRate();
        $daily_rate = $payroll->getDailyRate($salary);

        $philhealth = $payroll->getPhilHealth($salary);
        $sss = $payroll->getSSS($salary);
        $pagibig = $payroll->getPagibig($salary);


        $attendance = $payroll->getAttendance($id,$dfrom,$dto);
        $late = $payroll->getLate($daily_rate,$attendance);
        $absent = $payroll->getAbsence($daily_rate,$attendance);
        $undertime = $payroll->getUndertime($daily_rate,$attendance);

        $partial_deduction = $payroll->getDeduction($absent,$late,$undertime,$philhealth,$sss,$pagibig);
        
        $tax = $payroll->computeTax($emp,$partial_deduction);

        $gross = ($salary/2);

        $total_deduction = $payroll->getDeduction($absent,$late,$undertime,$philhealth,$sss,$pagibig,$tax['computed']);
        
        $netpay = $payroll->getNetpay($gross,$total_deduction);

        $data = array(
            'salary' => $salary,
            'philhealth' => $philhealth,
            'sss' => $sss,
            'pagibig' => $pagibig,
            'late' => $late,
            'undertime' => $undertime,
            'tax' => $tax['computed'],
            'absence' => $absent,
            'deduction' => $total_deduction,
            'gross' => $gross,
            'netpay' => $netpay
            );
        return new JsonResponse($data);  
    }

    public function update($o, $data, $is_new = false)
    {
        $wfm = $this->get('hris_workforce');
        $pay = $this->get('hris_payroll');
    }

    protected function padFormParams(&$params, $object = NULL){
        
        $em = $this->getDoctrine()->getManager();
            
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $settings = $this->get('hris_settings');
        $payroll = $this->get('hris_payroll');
        $params['dept_opts'] = $settings->getDepartmentOptions();
        $params['sched_opts'] = $payroll->getPayPeriodOptions();
        return $params;
    }
}