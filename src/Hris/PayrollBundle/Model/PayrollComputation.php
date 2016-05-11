<?php

namespace Hris\PayrollBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\PayrollBundle\Entity\PayTaxStatus;
use Hris\PayrollBundle\Entity\PayTaxRate;
use Hris\PayrollBundle\Entity\PayTaxMatrix;
use Hris\PayrollBundle\Entity\PayPeriod;
use Hris\PayrollBundle\Entity\PayPayroll;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\PayrollBundle\Entity\PayEarningEntry;
use Hris\PayrollBundle\Entity\PayPayrollPeriod;
use Hris\PayrollBundle\Entity\Pay13th;
use Hris\PayrollBundle\Entity\Pay13thEntry;
use Hris\WorkforceBundle\Entity\Attendance;
use Hris\AdminBundle\Entity\Benefit;
use Catalyst\UserBundle\Entity\User;

use Hris\PayrollBundle\Model\Deductions\SSSDeduction;
use Hris\PayrollBundle\Model\Deductions\PhilhealthDeduction;
use Hris\PayrollBundle\Model\Deductions\PagibigDeduction;
use Hris\PayrollBundle\Model\Deductions\LoanDeduction;
use Hris\PayrollBundle\Model\Deductions\CashbondDeduction;
use Hris\PayrollBundle\Model\Earnings\BasicEarning;
use Hris\PayrollBundle\Model\Earnings\ReimbursementEarning;
use Hris\PayrollBundle\Model\Earnings\IncentiveEarning;

use DateTime;

class PayrollComputation
{
    protected $em;
    protected $container;
    protected $configs;

    const EARN_GROSS = 'gross';
    const EARN_REIMBURSEMENT = 'reimbursement';
    const EARN_INCENTIVE = 'incentive';

    const DED_LOAN = 'loan';
    const DED_SSS = 'sss';
    const DED_PAGIBIG = 'pagibig';
    const DED_PHILHEALTH = 'philhealth';
    const DED_ADVANCE = 'advance';
    const DED_CASHBOND = 'cashbond';


    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }


    public function getAttendance($employee = null, $dfrom = null, $dto = null)
    {
        $am = $this->container->get('hris_attendance');

    	$query = "SELECT a FROM HrisWorkforceBundle:Attendance a WHERE (a.date BETWEEN :dfrom AND :dto) AND a.employee = :id";

    	$qry = $this->em->createQuery($query)
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

    /**
    * Check if a deduction should be deducted this payday
    */
    protected function checkDeductionSched($benefit_sched , $sched, $payPeriod){

        $payEnd = $payPeriod->getEndDate();
        //echo "checkDeductionSched function";

        // For weekly paid payroll, always deduct on the last pay of the month
        if($payPeriod->getPayPeriod()->getName() == PayPeriod::TYPE_WEEKLY){
             $eom = $payPeriod->getStartDate();
             $eom = new DateTime($eom->format( 'Y-m-t' ));

             if($eom >= $payPeriod->getStartDate() && $eom <= $payPeriod->getEndDate() ){
                return true;
             } else {
                return false;
             }

        }elseif($payPeriod->getPayPeriod()->getName() == PayPeriod::TYPE_SEMIMONTHLY){
            $curr = new DateTime();
            $end1 = clone $curr;
            $end1->setDate($end1->format('Y'), $payEnd->format('m'), $sched['cutoff_end1']);
            $end2 = clone $curr;
            $end2->setDate($end1->format('Y'), $payEnd->format('m'), $sched['cutoff_end2']);

            //what if im generating payroll for the past month?

            
            if($end1 > $curr){
                $end1->modify('-1 month');
            }

            if($end1 > $curr){
                $end2->modify('-1 month');
            }
            
            if($payEnd->format('Ymd') == $end1->format('Ymd') && $benefit_sched == 0){
                return true;
            }

            if($payEnd->format('Ymd') == $end2->format('Ymd') && $benefit_sched == 1){
                return true;
            }

            return false;
        }
        
    }

    protected function checkEmployeeBenefit($employee, $benefit)
    {
        $wm = $this->container->get('hris_workforce');
        switch ($benefit) {
            case self::DED_SSS:
                return $wm->hasEmployeeBenefit($employee, Benefit::NAME_SSS);
                break;

            case self::DED_PHILHEALTH:
                return $wm->hasEmployeeBenefit($employee, Benefit::NAME_PHILHEALTH);
                break;
            
            case self::DED_PAGIBIG:
                return $wm->hasEmployeeBenefit($employee, Benefit::NAME_PAGIBIG);
                break;
            case self::DED_CASHBOND:
                return $wm->hasEmployeeBenefit($employee, Benefit::NAME_CASHBOND);
                break;
        }

        return false;

    }

    protected function getDeductions($payroll)
    {
        $employee = $payroll->getEmployee();
        $pay_period = $payroll->getPayrollPeriod();
        $deductions = [];

        //No deductions if an employee was hired within the payroll period
        // if($employee->getDateHired() <= $pay_period->getStartDate()){
        //     return $deductions;
        // }


        if($this->checkDeductionSched($this->config['sss_sched'], $this->config['sched'], $pay_period) && $this->checkEmployeeBenefit($employee, self::DED_SSS))
            $deductions[self::DED_SSS] = SSSDeduction::getDeductionAmount($employee, $this->em);
        
        if($this->checkDeductionSched($this->config['philhealth_sched'], $this->config['sched'], $pay_period)  && $this->checkEmployeeBenefit($employee, self::DED_PHILHEALTH))
            $deductions[self::DED_PHILHEALTH] = PhilhealthDeduction::getDeductionAmount($employee, $this->em);
        
        if($this->checkDeductionSched($this->config['pagibig_sched'], $this->config['sched'], $pay_period) &&  $this->checkEmployeeBenefit($employee, self::DED_PAGIBIG))
            $deductions[self::DED_PAGIBIG] = PagibigDeduction::getDeductionAmount($employee, $this->em);

        if($this->checkEmployeeBenefit($employee, self::DED_CASHBOND))
            $deductions[self::DED_CASHBOND] = CashbondDeduction::getDeductionAmount($employee, $this->em);


        $deductions[self::DED_LOAN] = LoanDeduction::getDeductionAmount($payroll, $this->container);
        return $deductions;
    }

    protected function getEarnings( $payroll)
    {
        $earnings = [];

        $earnings[self::EARN_REIMBURSEMENT] = ReimbursementEarning::getEarningsAmount($payroll, $this->container);
        $earnings[self::EARN_GROSS] = BasicEarning::getEarningsAmount($payroll->getEmployee());
        $earnings[self::EARN_INCENTIVE] = IncentiveEarning::getEarningsAmount($payroll, $this->container);

        return $earnings;
    }


    public function getTaxStatus($employee)
    {
        if($employee->isExempted()){
            $status = "Z";
        }else{
            switch ($employee->getQualifiedDependents()) {
                case 0: $status = "ME/S";
                    break;
                case 1: $status = "ME1/S1";
                    break;
                case 2: $status = "ME2/S2";
                    break;
                 case 3: $status = "ME3/S3";
                    break;
                default:
                case 4: $status = "ME4/S4";
                    break;
            }
        }

        return $this->em->getRepository('HrisPayrollBundle:PayTaxStatus')->findOneByCode($status);
    }


    public function getAttendanceEarnings($employee, $pay_period)
    {
        $config = $this->container->get('catalyst_configuration');
        $ot_threshold = $config->get('hris_setting_overtime_threshold');
        

        $attendance = $this->getAttendance($employee, $pay_period->getStartDate(), $pay_period->getEndDate());

        $daily_rate = $employee->getDailyRate();
        $earnings = ['total' => 0, 'overtime' => 0, 'tardiness'=>0, 'absence' => 0, 'extra' => 0];
        foreach ($attendance as $day) {
            $final_rate = 0;
            $tardiness = 0;
            $overtime = 0;
            $holiday_pay = 0;
            $absence = 0;
            $multiplier = 1;
              
            //Employee is on leave
            if($day->getStatus() == Attendance::STATUS_PAIDLEAVE){
                $final_rate = $daily_rate;
                $tardiness = 0;
                $overtime = 0;
            }
            if($day->getStatus() == Attendance::STATUS_ABSENT){
                $absence = $daily_rate;
            }
            if($day->getTimeIn() != null){ //Employee is present
                $extra = true;
                switch($day->getStatus()){
                    case Attendance::STATUS_PRESENT:
                        $multiplier = 1;
                        $ot_multipler = 1.25;
                        $extra = false;
                        break;

                    //Halfday
                    case Attendance::STATUS_HALFDAY:
                        $multiplier = 0.5;
                        $extra = false;
                        break;

                    //Rest day and special holiday
                    case Attendance::STATUS_HOLIDAYNONWORKING:
                    case Attendance::STATUS_NONWORKING:
                        $multiplier = 1.3;
                        $ot_multipler = 1.3;
                        break;

                    // Regular holiday on a restday
                    case Attendance::STATUS_NONWORKINGRESTDAY:
                        $multiplier = 1.5;
                        $ot_multipler = 1.3;
                        break;

                    // Holiday
                    case Attendance::STATUS_HOLIDAY:
                        $multiplier = 2.0;
                        $ot_multipler = 1.3;
                        break;

                    //Holiday on a restday
                    case Attendance::STATUS_HOLIDAYRESTDAY:
                        $multiplier = 2.6;
                        $ot_multipler = 1.3;
                        break;

                    //Double holiday
                    case Attendance::STATUS_DOUBLEHOLIDAY:
                        $multiplier = 3.0;
                        $ot_multipler = 1.3;
                        break;

                    //Double holiday on a restday
                    case Attendance::STATUS_DOUBLEHOLIDAYRESTDAY:
                        $multiplier = 3.9;
                        $ot_multipler = 1.3;
                        break;
                }

                $final_rate = $daily_rate * $multiplier;
                $tardiness = $this->getMinuteRate($final_rate, $day->getLate());

                if($extra){
                    $holiday_pay = $final_rate;
                }
                if($ot_threshold != null && $day->getOvertime() >= $ot_threshold){
                    $overtime = $ot_multipler * $this->getMinuteRate($final_rate, $day->getOvertime());
                }
                //$nightshift = 1.1 * $this->getMinuteRate($final_rate, $day->getNightshit());

            }

            $earnings['total'] += $final_rate;
            $earnings['tardiness'] += $tardiness;
            $earnings['overtime'] += $overtime;
            $earnings['absence'] += $absence;
            $earnings['extra'] += $holiday_pay;
            //$earnings['nightshift'] += $nightshift;
        }

        return $earnings;

    }

    protected function getMinuteRate($daily_rate, $minutes)
    {
        return $daily_rate/480 * $minutes;
    }

    protected function computeEarnings($payroll)
    {
        $salary = ['taxable'=> 0, 'nontaxable'=>0];
        foreach($payroll->getEarningEntries() as $entry){
            if($entry->isTaxable())
                $salary['taxable'] += $entry->getAmount();
            else 
                $salary['nontaxable'] += $entry->getAmount();
        }

        foreach($payroll->getDeductionEntries() as $entry){
            if($entry->isTaxable())
                $salary['taxable'] -= $entry->getAmount();
             else 
                $salary['nontaxable'] -= $entry->getAmount();
        }

        return $salary;
    }

    public function applyTax(&$payroll)
    {
        $code = $this->getTaxStatus($payroll->getEmployee());
        $salary = $this->computeEarnings($payroll);
        //$salary = $salary['taxable'] <= 0 ? 1 : $salary['taxable'];


        $period = $payroll->getPayrollPeriod()->getPayPeriod();
        // $query = 'SELECT tm FROM HrisPayrollBundle:PayTaxMatrix tm INNER JOIN 
        //    HrisPayrollBundle:PayTaxRate tr WITH tm.rate_id = tr.id 
        //    WHERE (tr.amount_from <= :salary AND tr.amount_to >= :salary) AND tr.status = :status AND tm.period = :period';

        
        // $qry = $this->em->createQuery($query)
        //         ->setParameter('salary', $salary['taxable'] < 0 ? 2 : $salary['taxable'])
        //         ->setParameter('status', $code)
        //         ->setParameter('period', $period);
        // $tax = $qry->getSingleResult();


        
        $base_tax = 0;
        $base_amount = 0;
        $excess = 0;

        //$excess_tax = ($salary['taxable'] - $base_amount) * ($excess/100);
        $computed_tax = 0;

        $payroll->setTax($computed_tax);
        $payroll->setTotalTaxable($salary['taxable']);
        $payroll->setTotalNontaxable($salary['nontaxable']);
        $payroll->setTotal($salary['taxable'] - $computed_tax + $salary['nontaxable']);

        $this->em->persist($payroll);
        $this->em->flush();
    }

    protected function addPayrollEntries(&$payroll)
    {
        //$attendance = $this->getAttendanceEarnings($payroll->getEmployee(), $payroll->getPayrollPeriod());
        
        foreach ($payroll->getEarningEntries() as $ent)
            $this->em->remove($ent);
        $payroll->clearEarningEntries();

        foreach ($payroll->getDeductionEntries() as $ent2)
            $this->em->remove($ent2);
        $payroll->clearDeductionEntries();
        

        $this->em->persist($payroll);
        $this->em->flush();
        // Governement Deductions add check here
        $deductions = $this->getDeductions($payroll);
        foreach ($deductions as $key => $deduction) {
          error_log($key);
            switch ($key) {
                case self::DED_SSS: $dedEntry = new PayDeductionEntry();
                            $dedEntry->setAmount($deduction)
                            ->setType(PayDeductionEntry::TYPE_SSS);
                             $payroll->addDeductionEntry($dedEntry);
                            break;
                case self::DED_PHILHEALTH: $dedEntry = new PayDeductionEntry();
                            $dedEntry->setAmount($deduction)
                            ->setType(PayDeductionEntry::TYPE_PHILHEALTH);
                             $payroll->addDeductionEntry($dedEntry);
                            break;
                case self::DED_PAGIBIG:  $dedEntry = new PayDeductionEntry();
                            $dedEntry->setAmount($deduction)
                            ->setType(PayDeductionEntry::TYPE_PAGIBIG);
                             $payroll->addDeductionEntry($dedEntry);
                            break;
                case self::DED_CASHBOND:  $dedEntry = new PayDeductionEntry();
                            $dedEntry->setAmount($deduction)
                            ->setType(PayDeductionEntry::TYPE_CASHBOND)
                            ->setTaxable(false);
                             $payroll->addDeductionEntry($dedEntry);
                            break;
                 case self::DED_LOAN:
                            foreach($deduction as $loan){
                              $dedEntry = new PayDeductionEntry();
                              $dedEntry->setAmount($loan->getPayment())
                               ->setType(PayDeductionEntry::TYPE_COMPANYLOAN)
                               ->setNotes($loan->getLoan()->getType())
                               ->setTaxable(false);
                               $payroll->addDeductionEntry($dedEntry);
                            }
                            break;
            }           
        }


        //Attendance based earnings and deductions
        $timeEarnings = $this->getAttendanceEarnings($payroll->getEmployee(), $payroll->getPayrollPeriod());
        foreach ($timeEarnings as $key => $earning) {
            switch ($key) {
                case 'absence':
                            $entry = new PayDeductionEntry();
                             $entry->setAmount($earning)
                            ->setType(PayDeductionEntry::TYPE_ABSENT);
                            $payroll->addDeductionEntry($entry);
                            break;

                case 'tardiness': $entry = new PayDeductionEntry();
                             $entry->setAmount($earning)
                            ->setType(PayDeductionEntry::TYPE_TARDINESS);
                            $payroll->addDeductionEntry($entry);
                            break;

                case 'overtime': $entry = new PayEarningEntry();
                             $entry->setAmount($earning)
                            ->setType(PayEarningEntry::TYPE_OVERTIME);
                            $payroll->addEarningEntry($entry);
                            break;

                case 'extra': $entry = new PayEarningEntry();
                             $entry->setAmount($earning)
                            ->setType(PayEarningEntry::TYPE_HOLIDAY);
                            $payroll->addEarningEntry($entry);
                            break;
            }

        }

        // Earnings add check here
        $earnings = $this->getEarnings($payroll);
        foreach ($earnings as $key => $earning) {
           
            switch ($key) {
            case self::EARN_REIMBURSEMENT:  $entry = new PayEarningEntry();
                            $entry->setAmount($earning)
                            ->setType(PayEarningEntry::TYPE_REIMBURSEMENT)
                            ->setTaxable(false);
                             $payroll->addEarningEntry($entry);
                            break;
            case self::EARN_GROSS: $entry = new PayEarningEntry();
                             $entry->setAmount($earning)
                            ->setType(PayEarningEntry::TYPE_GROSS);
                             $payroll->addEarningEntry($entry);
                            break;
            case self::EARN_INCENTIVE:
                            foreach($earning as $incentive){
                              $entry = new PayEarningEntry();
                              $entry->setAmount($incentive->getCost())
                               ->setType(PayEarningEntry::TYPE_INCENTIVE)
                               ->setNotes($incentive->getType());
                               $payroll->addEarningEntry($entry);
                            }
                            break;
            }
       
        }


    }

    protected function generateEmployeePayroll($employee, $pay_period)
    {
        $payroll = $this->em->getRepository('HrisPayrollBundle:PayPayroll')
            ->findOneBy(array(
                'employee' => $employee,
                'payroll_period' => $pay_period)
            );



        if($payroll == null){
            $payroll = new PayPayroll();
            $payroll->setPayrollPeriod($pay_period)
                    ->setEmployee($employee);

            $this->em->persist($payroll);
            $this->em->flush();
        }

        //You can no longer change a locked payroll's computation
        if(!$payroll->isLocked()){
            $this->addPayrollEntries($payroll);
            //$this->container->get('hris_thirteenth')->add13thMonthEntry($payroll);
            $this->em->persist($payroll);
            $this->em->flush();
        
        }

        return $payroll;

    }

    public function generatePayPeriod($schedule, $date_from, $date_to)
    {
        $pay_period = $this->em->getRepository('HrisPayrollBundle:PayPayrollPeriod')
            ->findOneBy(array(
                'period' => $schedule,
                'start_date' => $date_from,
                'end_date' => $date_to));

        //If pay period is not found create a new entry
        if($pay_period == null){
            $fsmonth = $date_from->format('m');
            $fsyear = $date_from->format('Y');

            $pay_period = new PayPayrollPeriod();
            $pay_period->setPayPeriod($schedule)
                    ->setStartDate($date_from)
                    ->setEndDate($date_to)
                    ->setFiscalMonth($fsmonth)
                    ->setFiscalYear($fsyear);
            $this->em->persist($pay_period);
            $this->em->flush();
        }

        return $pay_period;
    }


    public function generatePayroll($schedule, $date_from, $date_to)
    {
        $wm = $this->container->get('hris_workforce');
        $config = $this->container->get('catalyst_configuration');
        //$pm = $this->container->get('hris_payroll');
                

        //$sched = $pm->getPayType($schedule);
        $employees = $wm->getEmployees(array('pay_sched'=> $schedule, 'enabled' => true, 'employment_status' => array('Probationary','Contractual','Regular')));
        $pay_period = $this->generatePayPeriod($schedule, $date_from, $date_to);


        //Load payroll configs to object
        if($pay_period->getPayPeriod()->getName() == PayPeriod::TYPE_SEMIMONTHLY){
            $this->config['sched'] = json_decode($config->get('hris_payroll_semimonthly_sched'), true);
            $this->config['sss_sched'] = $config->get('hris_payroll_semimonthly_payroll_sss');
            $this->config['philhealth_sched'] = $config->get('hris_payroll_semimonthly_payroll_philhealth');
            $this->config['pagibig_sched'] = $config->get('hris_payroll_semimonthly_payroll_pagibig');
        }elseif($pay_period->getPayPeriod()->getName() == PayPeriod::TYPE_WEEKLY) {
            $this->config['sched']  = json_decode($config->get('hris_payroll_weekly_sched'),true);
            $this->config['sss_sched'] = $config->get('hris_payroll_weekly_payroll_sss');
            $this->config['philhealth_sched'] = $config->get('hris_payroll_weekly_payroll_philhealth');
            $this->config['pagibig_sched'] = $config->get('hris_payroll_weekly_payroll_pagibig');
        }
        $employee_payroll = [];
        foreach ($employees as $employee) {
            $payroll = $this->generateEmployeePayroll($employee, $pay_period);//trace this
            $this->applyTax($payroll);
            $employee_payroll[] = $payroll;
        }
        //die();
        //die to see results


        return $employee_payroll;
    }

    public function getTaxAmount($payroll)
    {
        $code = $this->getTaxStatus($payroll->getEmployee());
        $salary = $this->computeEarnings($payroll);
        //$salary = $salary['taxable'] <= 0 ? 1 : $salary['taxable'];


        $period = $payroll->getPayrollPeriod()->getPayPeriod();
        $query = 'SELECT tm FROM HrisPayrollBundle:PayTaxMatrix tm INNER JOIN 
           HrisPayrollBundle:PayTaxRate tr WITH tm.rate_id = tr.id 
           WHERE (tr.amount_from <= :salary AND tr.amount_to >= :salary) AND tr.status = :status AND tm.period = :period';

        
        $qry = $this->em->createQuery($query)
                ->setParameter('salary', $salary['taxable'] < 0 ? 2 : $salary['taxable'])
                ->setParameter('status', $code)
                ->setParameter('period', $period);
        $tax = $qry->getSingleResult();

        
        $base_tax = $tax->getTaxRate()->getTax();
        $base_amount = $tax->getBaseAmount();
        $excess = $tax->getTaxRate()->getExcess();

        $excess_tax = ($salary['taxable'] - $base_amount) * ($excess/100);
        $computed_tax = $base_tax + $excess_tax;

        return array($tax->getTaxStatus()->getPersonal(), $tax->getTaxStatus()->getTotalExemption());
    }

}