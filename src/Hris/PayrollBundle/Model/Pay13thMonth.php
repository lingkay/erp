<?php

namespace Hris\PayrollBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\PayrollBundle\Entity\PayTaxStatus;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Hris\PayrollBundle\Entity\PayEarningEntry;
use Hris\PayrollBundle\Entity\Pay13th;
use Hris\PayrollBundle\Entity\Pay13thEntry;
use Hris\PayrollBundle\Entity\PayPeriod;


use Hris\WorkforceBundle\Entity\Attendance;
use Hris\AdminBundle\Entity\Benefit;
use Catalyst\UserBundle\Entity\User;

use DateTime;

class Pay13thMonth
{
    protected $em;
    protected $container;
    protected $configs;




    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function get13thMonthDetails($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:Pay13th')->find($id);
    }

    public function get13thMonthEntry($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:Pay13thEntry')->find($id);
    }

    public function persistPay13th(Pay13th $payroll)
    {
        $this->em->persist($payroll);
        $this->em->flush();
    }

    public function generate13thMonthEntity($employee)
    {
        $config = $this->container->get('catalyst_configuration');
        $yrend = $config->get('hris_payroll_weekly_year_end');
        
        $end = new DateTime();
        //$end->setTimestamp(strtotime($yrend));
        $year = $end->format('Y');
        $entry = $this->em->getRepository('HrisPayrollBundle:Pay13th')
            ->findOneBy(array(
                'employee' => $employee,
                'year' => $year)
            );

        if($entry == null){
            $entry = new Pay13th();
            $entry->setYear($year)
                    ->setEmployee($employee);

            $this->em->persist($entry);
            $this->em->flush();
        }
        return $entry;
    }

    public function generate13thMonthEntry($employee, $period)
    {
        $entry = $this->em->getRepository('HrisPayrollBundle:Pay13thEntry')
            ->findOneBy(array(
                'employee' => $employee,
                'payroll_period' => $period)
            );

        if($entry == null){
            $main = $this->generate13thMonthEntity($employee);
            $entry = new Pay13thEntry();
            $entry->setPayrollPeriod($period)
                    ->setPay13th($main)
                    ->setEmployee($employee);

            $this->em->persist($entry);
            $this->em->flush();
        }

        return $entry;
    }

    public function add13thMonthEntry($payroll)
    {
        $entry = $this->generate13thMonthEntry($payroll->getEmployee(), $payroll->getPayrollPeriod());

        $deduction = $payroll->getDeductionEntry(PayDeductionEntry::TYPE_ABSENT);
        $earning = $payroll->getEarningEntry(PayEarningEntry::TYPE_GROSS);

        $entry->setDeduction($deduction->getAmount());
        $entry->setEarning($earning->getAmount());
        $entry->setTotal($earning->getAmount() - $deduction->getAmount());

        $this->em->persist($entry);
        $this->em->flush();
    }

    public function applyTax(&$pay13th){
        $pc = $this->container->get('hris_payroll_compute');
        $pm = $this->container->get('hris_payroll');

        $code = $pc->getTaxStatus($pay13th->getEmployee());
        $period = $pm->getPayPeriodByType(PayPeriod::TYPE_MONTHLY);
       $query = 'SELECT tm FROM HrisPayrollBundle:PayTaxMatrix tm INNER JOIN 
       HrisPayrollBundle:PayTaxRate tr WITH tm.rate_id = tr.id 
       WHERE (tr.amount_from <= :salary AND tr.amount_to >= :salary) AND tr.status = :status AND tm.period = :period';

        $qry = $this->em->createQuery($query)
        ->setParameter('salary', $pay13th->getTotalTaxable() <= 0 ? 2 : $pay13th->getTotalTaxable())
        ->setParameter('status', $code)
        ->setParameter('period', $period);
        $tax = $qry->getSingleResult();

        
        $base_tax = $tax->getTaxRate()->getTax();
        $base_amount = $tax->getBaseAmount();
        $excess = $tax->getTaxRate()->getExcess();

        $excess_tax = ($pay13th->getTotalTaxable() - $base_amount) * ($excess/100);
        $computed_tax = $base_tax + $excess_tax;

        $pay13th->setTax($computed_tax);
        $pay13th->setTotal($pay13th->getTotalTaxable() - $computed_tax);
        
        $this->em->persist($pay13th);
        $this->em->flush();
    }

    protected function getYearPayPeriod($period,$start, $end)
    {
        $query = 'SELECT pp FROM HrisPayrollBundle:PayPayrollPeriod pp 
        WHERE pp.start_date >= :start_date AND pp.end_date >= :end_date AND pp.period = :period';

        $qry = $this->em->createQuery($query)
            ->setParameter('start_date', $start)
            ->setParameter('end_date', $end)
            ->setParameter('period', $period);

        $periods = $qry->getResult();

        return $periods;
    }

    protected function getAttendanceDeduction($employee, $period)
    {
        $pr = $this->container->get('hris_payroll_compute');
        $attendance = $pr->getAttendance($employee, $period->getStartDate(), $period->getEndDate());
        $daily_rate = $this->getDailyRateAtPeriod($employee, $period);
        $earnings = $this->getEarnings($employee, $period);
        

        $absence = 0;
        foreach ($attendance as $day) {
            if($day->getStatus() == Attendance::STATUS_ABSENT){
                $absence += $daily_rate;
            }
        }

        return array('earnings'=> $earnings, 'absences' => $absence);
    }

    protected function getEarnings($employee ,$period){

        switch($period->getPayPeriod()->getName()){
            case PayPeriod::TYPE_WEEKLY : return $this->getWeeklyRateAtPeriod($employee,$period);
                break;
            case PayPeriod::TYPE_SEMIMONTHLY : return $this->getSemiMonthlyRateAtPeriod($employee,$period);
                break;
        }
    }

    protected function getDailyRateAtPeriod($employee, $period)
    {
         $rate = $this->getRateAtPeriod($employee, $period);
        switch($employee->getPayPeriod()->getName())
        {
            //Weekly
            case PayPeriod::TYPE_WEEKLY : $computed = $rate/6;
                    break;

            //Semi Monthly
            case PayPeriod::TYPE_SEMIMONTHLY: $computed = $rate/12;
                    break;

            //Monthly
            case PayPeriod::TYPE_MONTHLY: 
            default: $computed = $rate * 12/313;
                    break;

            //Daily
            case PayPeriod::TYPE_DAILY : 
            default : $computed = $rate;
                    break;
        }
        return floor($computed * 100) / 100;
    }

    protected function getSemiMonthlyRateAtPeriod($employee, $period)
    {
        $rate = $this->getRateAtPeriod($employee, $period);
        switch($employee->getPayPeriod()->getName())
        {

            //Semi Monthly
            case PayPeriod::TYPE_SEMIMONTHLY: $computed = $rate;
                    break;

            //Monthly
            case PayPeriod::TYPE_MONTHLY: 
            default: $computed = $rate/2;
                    break;

        }
        return floor($computed * 100) / 100;
    }

    protected function getWeeklyRateAtPeriod($employee, $period)
    {
        $rate = $this->getRateAtPeriod($employee, $period);
        switch($employee->getPayPeriod()->getName())
        {

            //Semi Monthly
            case PayPeriod::TYPE_WEEKLY: $computed = $rate;
                    break;

            //Monthly
            case PayPeriod::TYPE_DAILY: 
            default: $computed = $rate/6;
                    break;

        }
        return floor($computed * 100) / 100;
    }

    protected function getRateAtPeriod($employee, $period)
    {
        if($employee->getDateHired() > $period->getStartDate()){
            return 0;
        }
        $query = "SELECT a FROM HrisWorkforceBundle:SalaryHistory a WHERE a.date_create <= :dfrom  AND a.employee = :id ORDER BY a.date_create ASC ";

        $qry = $this->em->createQuery($query)
            ->setParameter('id', $employee)
            ->setParameter('dfrom', $period->getStartDate())
            ->setMaxResults(1);

        $history = $qry->getResult();
        if($history == null){
            return 0;
        }else {
            return $history[0]->getPay();
        }
    }

    public function generateEmployee13th($employee, $start,$end,$year)
    {
        $entity = $this->generate13thMonthEntity($employee);
        $curr = new DateTime();
        $total_earnings = 0;
        $count = 0;
        $periods = $this->getYearPayPeriod($employee->getPaySchedule(),$start,$end);
        foreach($periods as $period){
            if($period->getEndDate() > $curr){
                break;
            }
            $pd = $this->generate13thMonthEntry($employee, $period);
            if($pd->getTotal() <= 0){
                $computed = $this->getAttendanceDeduction($employee, $period);
                $pd->setEarning($computed['earnings']);
                $pd->setDeduction($computed['absences']);
                $total =$computed['earnings'] - $computed['absences'];
                $pd->setTotal($total<=0? 0: $total);
                $pd->setPay13th($entity);

                $this->em->persist($pd);
                $this->em->flush();
            }
            $total_earnings += $pd->getTotal();
            $count++;
        }

        if($count != 0){
            $entity->setTotalTaxable($total_earnings/$count);
             $entity->recompute();
        }
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }


    public function generate13th($start,$end,$year)
    {
        $wm = $this->container->get('hris_workforce');
        $config = $this->container->get('catalyst_configuration');
        //$pm = $this->container->get('hris_payroll');
                

        //$sched = $pm->getPayType($schedule);
        $employees = $wm->getEmployees(array('enabled' => true));
        //$pay_period = $this->generatePayPeriod($schedule, $date_from, $date_to);


        $employee_payroll = [];
        foreach ($employees as $employee) {
            $payroll = $this->generateEmployee13th($employee, $start, $end, $year);
            $this->applyTax($payroll);
            $employee_payroll[] = $payroll;
        }

        return $employee_payroll;
    }

}