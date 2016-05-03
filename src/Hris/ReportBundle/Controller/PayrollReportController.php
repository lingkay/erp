<?php

namespace Hris\ReportBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Catalyst\ValidationException;
use Hris\PayrollBundle\Entity\PayPeriod;
use Hris\PayrollBundle\Entity\PayEarningEntry as Earn;
use Hris\PayrollBundle\Entity\PayDeductionEntry as Deduct;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Hris\WorkforceBundle\Entity\Attendance;
use DateTime;
use SplFileObject;
use LimitIterator;

class PayrollReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_payroll';
		$this->title = 'Payroll Report';

		$this->list_title = 'Payroll Report';
		$this->list_type = 'static';
        $this->repo = 'HrisPayrollBundle:PayPayroll';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $em = $this->getDoctrine()->getManager();

        $params = $this->getViewParams('List', 'hris_report_payroll_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:Payroll:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        // FOR REPORT
        // $params['title_opts'] = $settings->getJobTitleOptions();
        // $params['level_opts'] = $settings->getJobLevelOptions();
        // $params['status_opts'] = $settings->getEmploymentStatusOptions();
        // $params['location_opts'] = $settings->getLocationOptions();
        // $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');

        $periods = $em->getRepository('HrisPayrollBundle:PayPeriod')->findAll();

        foreach ($periods as $period) {
            $params['pay_type'][$period->getID()] = $period->getName();
        }

        return $this->render($twig_file, $params);
    }

    public function gridPayAction()
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
        
            $qry[] = "(period.end_date <= '".$dto->format('Y-m-d')."' AND period.start_date >= '".$dfrom->format('Y-m-d')."')";
        } 

        if (isset($data['pay_type']) and $data['pay_type'] != NULL) {
            $qry[] = "(employee.pay_sched = '".$data['pay_type']."')";
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
            $grid->newJoin('period', 'payroll_period', 'getPayrollPeriod', 'left'),
            $grid->newJoin('employee', 'employee', 'getEmployee'),

        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array( 
            $grid->newColumn('Employee', 'getDisplayName', 'last_name','employee'),
            $grid->newColumn('Payroll Period', 'getPayrollPeriod', '','o', array($this,'formatPaySchedule')),
            $grid->newColumn('Gross Pay', 'getTotal', 'total_amount','o', array($this,'formatPrice')),
        );
    }

    public function formatPaySchedule($payPeriod)
    {
        return $payPeriod->getStartDate()->format('m/d/Y').' - '.$payPeriod->getEndDate()->format('m/d/Y');
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

    public function headers()
    {
        $headers['csv_headers_1'] = ['PERIOD CUT-OFF','(Date)','EARNINGS','','','','DEDUCTIONS','','','','',''];
        $headers['csv_headers_2'] = ['LAST NAME','FIRST NAME','BASE PAY', 'DAILY RATE', 'ECOLA', 'Allowance', 'TOTAL', 'NO. OF WORKDAYS', 'Semi-Monthly Basic Pay', 'Semi-Monthly Allowance', 'LATE', 'DEDUCTION', 'UNDERTIME', 'ABSENCES', 'TOTAL DEDUCTION', 'ADDITION ADJUSTMENT', 'REGULAR OT', 'OTRD', 'SUNDAY', 'HOLIDAY', '   ', 'SSS', 'PHILHEALTH', 'PAG-IBIG', 'EMPLOYEE CONTRIBUTION', 'TOTAL', 'CIVIL STATUS', 'EXEMPTION', 'NET EXEMPTION', 'TAX AMOUNT', 'NET PAY AFTER TAX', 'SSS LOAN', 'PAG-IBIG LOAN', 'HMO', 'GIFT CHECK', 'CASH BOND', 'OTHERS', 'MOBILE PHONE CHARGES', 'PURCHASES', 'CASH ADVANCE', 'TOTAL LOANS', 'INCENTIVE', 'ADD ECOLA', 'NET PAY', 'REMARKS'];
        return $headers;
    }

    private function getCSVdata($data)
    {
        $em = $this->getDoctrine()->getManager();
        $dfrom = $data['date_from'] == 'null' ? new DateTime($date->format('Ym01')):new DateTime($data['date_from']);
        $dto = $data['date_to'] == 'null' ? new DateTime($date->format('Ymt')):new DateTime($data['date_to']);
        // filter
        $qry = $em->createQueryBuilder();
        $qry->from('HrisPayrollBundle:PayPayroll', 'o');
        $qry->join('HrisWorkforceBundle:Employee','e','WITH','o.employee=e.id');
        $qry->join('HrisPayrollBundle:PayPayrollPeriod','pp','WITH','o.payroll_period=pp.id');

        if (isset($data['emp_id']) and $data['emp_id'] != NULL) {
            $qry->andwhere("o.employee = '".$data['emp_id']."'");
        }

        if (isset($data['department']) and $data['department'] != NULL ) {
            $qry->andwhere("o.employee IN (SELECT x.id FROM HrisWorkforceBundle:Employee x WHERE x.department = '".$data['department']."')");
        }

        if (isset($data['date_from']) and $data['date_from'] != NULL) {
            $dfrom2 = $data['date_from']=='null'? new DateTime():new DateTime($data['date_from'].'00:00:00');
            $dto2 = $data['date_to']=='null'? new DateTime():new DateTime($data['date_to'].'23:59:59');
        
            $qry->andwhere("pp.end_date <= '".$dto2->format('Y-m-d')."' AND pp.start_date >= '".$dfrom2->format('Y-m-d')."'");
        } 

        if (isset($data['pay_type']) and $data['pay_type'] != NULL) {
            $qry->andwhere("e.pay_sched = '".$data['pay_type']."'");
        }

        $filtered = $qry->select('o')
                    ->getQuery()
                    ->getResult();

        return $filtered;
    }

    public function printPayAction()
    {
        $data = $this->getRequest()->request->all();
        $em = $this->getDoctrine()->getManager();
        $pm = $this->get('hris_payroll');
        $pc = $this->get('hris_payroll_compute');
        $dfrom = $data['date_from'] == 'null' ? new DateTime($date->format('Ym01')):new DateTime($data['date_from']);
        $dto = $data['date_to'] == 'null' ? new DateTime($date->format('Ymt')):new DateTime($data['date_to']);
        $filtered = $this->getCSVdata($data);

        $date = new DateTime();
        $filename = 'Payroll Overview Report ('. $dfrom->format('mdY') .'-'. $dto->format('mdY') .').csv';
        $file = fopen('php://output', 'w');
        ob_start();
        $headers = $this->headers();
        fputcsv($file, $headers['csv_headers_1']);
        fputcsv($file, $headers['csv_headers_2']);
        
        foreach ($filtered as $data)
        {
            $payroll = $pm->getPayPayroll($data->getID());
            $datetime1 = new DateTime($data->getPayrollPeriod()->getStartDate()->format("Y-m-d"));//refactor
            $datetime2 = new DateTime($data->getPayrollPeriod()->getEndDate()->format("Y-m-d"));//refactor
            $interval = $datetime1->diff($datetime2);
            $no_of_work_days = $interval->format('%a');
            $arr = array_fill(0, 44, 0);
            $rd_earnings = $this->getRestDayEarnings($data);
            $incentive = 0;
            $deductions = $this->getDeductions($payroll);
            list($sss, $pagibig, $philhealth, $others, $absent, $undertime, $tardiness, $hmo, $gc, $phone_charges, $purchases, $sss_loan, $pagibig_loan, $cashbond) = $deductions;
            $tax = $pc->getTaxAmount($payroll);

            foreach ($payroll->getEarningEntries() as $entry) {
                switch ($entry->getType()) {
                    case Earn::TYPE_GROSS:
                        // $arr[2] = $entry->getAmount();
                        break;
                    case Earn::TYPE_OVERTIME:
                        $arr[16] = $entry->getAmount();
                        break;
                    case Earn::TYPE_HOLIDAY:
                        $arr[19] = $entry->getAmount();
                        break;
                    case Earn::TYPE_INCENTIVE:
                        $incentive = $incentive + $entry->getAmount();
                        $arr[41] = $incentive;
                        break;
                    case Earn::TYPE_OTHERS:
                        if ($entry->getNotes() == "ecola" || $entry->getNotes() == "ECOLA") 
                        {
                            $arr[4] = $entry->getAmount();
                        }
                        break;
                }
            }

            $arr[0]  = $data->getEmployee()->getLastName();
            $arr[1]  = $data->getEmployee()->getFirstName();
            $arr[2]  = $data->getEmployee()->getPayRate();
            $arr[3]  = $data->getEmployee()->getDailyRate();
            //ALLOWANCE
            $arr[5]  = 0;
            $arr[6]  = $arr[2] + $arr[5];
            $arr[7]  = $no_of_work_days;
            $arr[8]  = $arr[2]/2;
            //SEMI-MONTHLY ALLOWANCE
            $arr[9]  = 0;
            //ATTENDANCE DEDUCTIONS
            $arr[10] = $tardiness;
            $arr[11] = 0; //DEDUCTION(?)
            $arr[12] = $undertime;
            $arr[13] = $absent;
            $arr[14] = $others + $absent + $undertime + $tardiness;
            //ADDITION ADJUSTMENT
            $arr[15] = 0;
            //OT-HOLIDAY-RESTDAY EARNINGS
            $arr[17] = $rd_earnings[1];
            $arr[18] = $rd_earnings[0];
            //ADD OT-HOLIDAY-RESTDAY EARNINGS
            $arr[20] = round($data->getEmployee()->getPayRate()/2 - $arr[14] + ($arr[16] + $arr[19]), 2);
            //DEDUCTIONS
            $arr[21] = $sss;
            $arr[22] = $philhealth;
            $arr[23] = $pagibig;
            $arr[24] = $sss + $pagibig + $philhealth;
            //TOTAL DEDCUTION
            $arr[24] = $arr[21] + $arr[22] + $arr[23];
            //TAXES
            $arr[25] = $arr[20] - $arr[24]; 
            $arr[26] = $data->getEmployee()->getMaritalStatus();
            $arr[27] = $tax[0]; 
            $arr[28] = $tax[1]; 
            $arr[29] = $payroll->getTax();
            //NET PAY AFTER TAX
            $arr[30] = $arr[25] - $arr[29];
            //LOANS
            $arr[31] = $sss_loan;
            $arr[32] = $pagibig_loan;
            $arr[33] = $hmo; 
            $arr[34] = $gc;
            $arr[35] = $cashbond;
            $arr[36] = $others;
            $arr[37] = $phone_charges;
            $arr[38] = $purchases;
            $arr[39] = $this->getCashAdvances($payroll, $datetime1, $datetime2);
            //TOTAL LOANS
            $arr[40] = $arr[31]+$arr[32]+$arr[33]+$arr[34]+$arr[35]+$arr[36]+$arr[37]+$arr[38]+$arr[39];
            //ADD ECOLA
            $arr[42] = 0;
            //GROSS PAY/TOTAL
            $arr[43] = $data->getTotal();

            ksort($arr);
            fputcsv($file, $arr);
        }

        fclose($file);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }

    protected function getCashAdvances($payroll, $dfrom, $dto)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->container->get('hris_attendance');
        $ca_amount = 0;

        $query = "SELECT a FROM HrisWorkforceBundle:Advance a WHERE (a.date_released BETWEEN :dfrom AND :dto) AND a.employee = :id";

        $qry = $em->createQuery($query)
            ->setParameter('id', $payroll->getEmployee())
            ->setParameter('dfrom', $dfrom)
            ->setParameter('dto', $dto);

        $advances = $qry->getResult();

        foreach ($advances as $ca) 
        {
            $ca_amount += $ca->getAmount();
        }

        return $ca_amount;
    }

    protected function getDeductions($payroll)
    {
        $de_sss = 0;
        $de_pagibig = 0;
        $de_philhealth = 0;
        $de_others = 0;
        $de_absent = 0;
        $de_undertime = 0;
        $de_tardiness = 0;
        $de_cashbond = 0;
        $de_hmo = 0;
        $de_gc = 0;
        $de_phone_charges = 0;
        $de_purchases = 0;
        $de_sss_loan = 0;
        $de_pagibig_loan = 0;
        foreach ($payroll->getDeductionEntries() as $entry) 
        {
            switch ($entry->getType()) 
            {
                case Deduct::TYPE_SSS:
                    $de_sss = $entry->getAmount();
                    break;
                case Deduct::TYPE_CASHBOND:
                    $de_cashbond = $entry->getAmount();
                    break;
                case Deduct::TYPE_PAGIBIG:
                    $de_pagibig = $entry->getAmount();
                    break;
                case Deduct::TYPE_PHILHEALTH:
                    $de_philhealth = $entry->getAmount();
                    break;
                case Deduct::TYPE_OTHERS:
                    if ($entry->getNotes() == "hmo") 
                    {
                        $de_hmo = $entry->getAmount();
                    }
                    elseif ($entry->getNotes() == "gift check") 
                    {
                        $de_gc = $entry->getAmount();
                    }
                    elseif ($entry->getNotes() == "mobile phone charges") 
                    {
                        $de_phone_charges = $entry->getAmount();
                    }
                    elseif ($entry->getNotes() == "purchases") 
                    {
                        $de_purchases = $entry->getAmount();
                    }
                    elseif ($entry->getNotes() == "sss loan") 
                    {
                        $de_sss_loan = $entry->getAmount();
                    }
                    elseif ($entry->getNotes() == "pag-ibig loan") 
                    {
                        $de_pagibig_loan = $entry->getAmount();
                    }
                    else
                    {
                        $de_others = $entry->getAmount();
                    }
                    break;  
                case Deduct::TYPE_ABSENT:
                    $de_absent = $entry->getAmount();
                    break;
                case Deduct::TYPE_UNDERTIME:
                    $de_undertime = $entry->getAmount();
                    break;
                case Deduct::TYPE_TARDINESS:
                    $de_tardiness = $entry->getAmount();
                    break;
            }
        }
        return array($de_sss, $de_pagibig, $de_philhealth, $de_others, $de_absent, $de_undertime, $de_tardiness, $de_hmo, $de_gc, $de_phone_charges, $de_purchases, $de_sss_loan, $de_pagibig_loan, $de_cashbond);
    }

    protected function getRestDayEarnings($data)
    {
        $total_overtime = 0;
        $total_final_rate = 0;
        $attendance = $this->getAttendance($data->getEmployee()->getID(), $data->getPayrollPeriod()->getStartDate(), $data->getPayrollPeriod()->getEndDate());
        $daily_rate = $data->getEmployee()->getDailyRate();
        foreach ($attendance as $day) 
        {
            if (date('N', strtotime($day->getDate()->format('Y-m-d'))) == 7)
            {
                $multiplier = 1.3;
                $ot_multipler = 1.3;
                $ot_threshold = 0;
                $overtime = 0;
                $config = $this->container->get('catalyst_configuration');
                $ot_threshold = $config->get('hris_setting_overtime_threshold');
                $final_rate = $daily_rate * $multiplier;
                $tardiness = $this->getMinuteRate($final_rate, $day->getLate());

                if($ot_threshold != null && $day->getOvertime() >= $ot_threshold){
                    $overtime = $ot_multipler * $this->getMinuteRate($final_rate, $day->getOvertime());
                }
                $total_final_rate += number_format($final_rate, 2, '.', ' ');
                $total_overtime += number_format($overtime, 2, '.', ' ');
            }
        }

        $earnings = array($total_final_rate, $total_overtime);
        return $earnings;
    }

    protected function getMinuteRate($daily_rate, $minutes)
    {
        return $daily_rate/480 * $minutes;
    }

    public function getAttendance($employee = null, $dfrom = null, $dto = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->container->get('hris_attendance');

        $query = "SELECT a FROM HrisWorkforceBundle:Attendance a WHERE (a.date BETWEEN :dfrom AND :dto) AND a.employee = :id";

        $qry = $em->createQuery($query)
            ->setParameter('id', $employee)
            ->setParameter('dfrom', $dfrom)
            ->setParameter('dto', $dto);

        $attendance = $qry->getResult();

        $attend = array();
        foreach ($attendance as $day) {
            $attend[$day->getDate()->format('Ymd')] = $day;
        }

        $datetemp = clone $dfrom;
        while ($datetemp <= $dto) {
            if(!isset($attend[$datetemp->format('Ymd')])){
                $attend[$datetemp->format('Ymd')] = $am->fillDate($datetemp, $employee);
            }
            $datetemp->add(date_interval_create_from_date_string('1day'));
        }
        return $attend;
    }
}