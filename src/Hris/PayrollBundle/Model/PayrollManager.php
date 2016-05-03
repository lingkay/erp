<?php

namespace Hris\PayrollBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\WorkforceBundle\Entity\Employee;
use Hris\WorkforceBundle\Entity\Profile;
use Hris\PayrollBundle\Entity\PayPeriod;
use Hris\PayrollBundle\Entity\PayPayrollPeriod;
use Hris\PayrollBundle\Entity\PayPayroll;
use Hris\PayrollBundle\Entity\PayEarningEntry;
use Hris\PayrollBundle\Entity\PayDeductionEntry;
use Catalyst\UserBundle\Entity\User;

use DateTime;

class PayrollManager
{
    protected $em;
    protected $container;
    protected $configs;

    public function __construct(EntityManager $em, $container)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getPayType($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayPeriod')->find($id);
    }

    public function getPayPeriodByType($type)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayPeriod')->findOneByName($type);
    }

        public function getPaySchedule($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:PaySchedule')->find($id);
    }

    public function getPayPeriodOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisPayrollBundle:PayPeriod')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $list_opts = array();
        foreach ($opts as $item)
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getPayPayroll($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayPayroll')->find($id);
    }

    public function getPaySchedOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisPayrollBundle:PayPeriod')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $list_opts = array();
        foreach ($opts as $item)
            if($item->getName() == PayPeriod::TYPE_SEMIMONTHLY || $item->getName() == PayPeriod::TYPE_MONTHLY ||
               $item->getName() == PayPeriod::TYPE_WEEKLY || $item->getName() == PayPeriod::TYPE_DAILY )
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getPaySchedPayrollOptions($filter = array())
    {
        $opts = $this->em
            ->getRepository('HrisPayrollBundle:PayPeriod')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $list_opts = array();
        foreach ($opts as $item)
            if($item->getName() == PayPeriod::TYPE_SEMIMONTHLY ||
               $item->getName() == PayPeriod::TYPE_WEEKLY  )
            $list_opts[$item->getID()] = $item->getName();

        return $list_opts;
    }

    public function getPayScheduleOptions($filter = array())
    {
          $schedules = $this->em
            ->getRepository('HrisPayrollBundle:PaySchedule')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $sched_opts = array();
        foreach ($schedules as $schedule)
            $sched_opts[$schedule->getID()] = $schedule->getName();

        return $sched_opts;
    }

    public function newPayrollEarning()
    {
        return new PayEarningEntry();
    }

    public function newPayrollDeduction()
    {
        return new PayDeductionEntry();
    }

    public function getPayEarning($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayEarningEntry')->find($id);
    }

    public function getPayDeduction($id)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayDeductionEntry')->find($id);
    }


    public function persistPayroll(PayPayroll $payroll)
    {
        $this->em->persist($payroll);
        $this->em->flush();
    }

    public function dayLocked($employee, $date)
    {
        // $period = $payroll->getPayrollPeriod()->getPayPeriod();
        $query = 'SELECT p FROM HrisPayrollBundle:PayPayroll p INNER JOIN 
           HrisPayrollBundle:PayPayrollPeriod pr WITH p.payroll_period = pr.id 
           WHERE ( :cdate BETWEEN pr.start_date AND pr.end_date ) AND p.employee = :employee';
     
        $qry = $this->em->createQuery($query)
            ->setParameter('employee', $employee)
            ->setParameter('cdate', $date);
        
        $return = $qry->getOneOrNullResult();

        if($return == null || $return->isLocked() == false){
            return false;
        } else {
            return true;
        }
    }

    public function getEmployeePayrolls($employee)
    {
        return $this->em->getRepository('HrisPayrollBundle:PayPayroll')
        ->findBy(
                array('employee' => $employee),
                array('id' => 'DESC')
            );
    }

    public function generateWeeklyPayPeriod($start, $end)
    {
        $conf = $this->container->get('catalyst_configuration');
        $pc = $this->container->get('hris_payroll_compute');
        $weekly = json_decode($conf->get('hris_payroll_weekly_sched'),true);

        $curr = new DateTime();
        $start_date = new DateTime();
        $start_date->setTimestamp(strtotime($start));
        $end_date = new DateTime();
        $end_date->setTimestamp(strtotime($end));
        $period = $this->getPayPeriodByType(PayPeriod::TYPE_WEEKLY);
 
        if($start_date > $curr){
            $start_date->modify('-1 year');
        }
        if($start_date->format('w') != $weekly['cutoff_start']){
           // Add nearest day here
        }else {
            while ($start_date <= $end_date) {
                $wstart = clone $start_date;
                $wend = clone $start_date;
                $wend->modify('+6 days');
               
                $pc->generatePayPeriod($period,$wstart,$wend);
                $start_date->modify('+7 days');
            }
        }
    }

    public function generateSemiMonthlyPayPeriod($start, $end)
    {
        $conf = $this->container->get('catalyst_configuration');
         $pc = $this->container->get('hris_payroll_compute');
      
        $curr = new DateTime();
        $start_date = new DateTime();
        $start_date->setTimestamp(strtotime($start));
        $end_date = new DateTime();
        $end_date->setTimestamp(strtotime($end));

        $period = $this->getPayPeriodByType(PayPeriod::TYPE_SEMIMONTHLY);
        $sched = json_decode($conf->get('hris_payroll_semimonthly_sched'), true);
        if($start_date > $curr){
            $start_date->modify('-1 year');
        }
        while ($start_date <= $end_date) {
            $start1 = clone $start_date;
            $start1->setDate($start_date->format('Y'), $start_date->format('m'), $sched['cutoff_start1']);
            $end1 = clone $start_date;
            $end1->setDate($start_date->format('Y'), $start_date->format('m'), $sched['cutoff_end1']);
            if($end1 < $start1){
                $end1->modify('+1 month');
            }
            $start2 = clone $start_date;
            $start2->setDate($start_date->format('Y'), $start_date->format('m'), $sched['cutoff_start2']);
            $end2 = clone $start_date;
            $end2->setDate($start_date->format('Y'), $start_date->format('m'), $sched['cutoff_end2']);
            
            if($end2 < $start2){
                $end2->modify('+1 month');
            }
            $pc->generatePayPeriod($period,$start1,$end1);
            $pc->generatePayPeriod($period,$start2,$end2);
            $start_date->modify('+1 month');
        }
    }

    public function generatePayPeriods()
    {
        $conf = $this->container->get('catalyst_configuration');
       
        $week_start = $conf->get('hris_payroll_weekly_year_start');
        $week_end = $conf->get('hris_payroll_weekly_year_end');

        $this->generateWeeklyPayPeriod($week_start, $week_end);
        $semi_start = $conf->get('hris_payroll_semi_year_start');
        $semi_end = $conf->get('hris_payroll_semi_year_end');

        $this->generateSemiMonthlyPayPeriod($semi_start, $semi_end);

    }

    protected function parseDay($day)
    {
        $now = new Datetime();
        if($day == 0){
             return $now->setDate($now->format('Y'),$now->format('m'), $now->format('t')  );
        }

        if($day > (integer) $now->format('d')){
            return $now->setDate($now->format('Y'),$now->format('m') - 1, $day );
        }else {
            return $now->setDate($now->format('Y'),$now->format('m'), $day );
        }

    }

    protected function parseRange($from, $to,$currentMonth){
        $from = $this->parseDay($from,$currentMonth);
        $to = $this->parseDay($to,$currentMonth);
        // $now = new DateTime();
        if($from > $to){
            $from->setDate($from->format('Y'),$from->format('m') - 1, $from->format('d') );
        }
        return [$from,$to];
    }


}