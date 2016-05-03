<?php

namespace Hris\PayrollBundle\Model;

use Doctrine\ORM\EntityManager;
use Hris\PayrollBundle\Entity\PayPeriod;
use DateTime;

class PaySettings
{
    protected $container;
    protected $conf;
    public function __construct($container)
    {
        $this->container = $container;
        $this->conf = $this->container->get('catalyst_configuration');
    }

    protected function getLastWeekCutoff()
    {
        $curr = new DateTime();
        $cutoff = json_decode($this->conf->get('hris_payroll_weekly_sched'));
       
    }

    protected function getLastSemimonthlyCutoff()
    {
        $curr - new DateTime();
        $cutoff = json_decode($this->conf->get('hris_payroll_semimonthly_sched'));
        if($curr->format('j') > $cutoff['cutoff_end1'] ){
            $start = new DateTime();
            $start->setDate($curr->format('Y'),$curr->format('m'), $cutoff['cutoff_start1']);

            if($start > $curr){
                $start->modify('-1 month');
            }

            $end = new DateTime();
            $end->setDate($curr->format('Y'),$curr->format('m'), $cutoff_end1);
        }else {
            $start = new DateTime();
            $start->setDate($curr->format('Y'),$curr->format('m'), $cutoff_start2);

            if($start > $curr){
                $start->modify('-1 month');
            }

            $end = new DateTime();
            $end->setDate($curr->format('Y'),$curr->format('m'), $cutoff_end2);
        }

        return [$start, $end];
    }

    protected function getLastCutoff(PayPeriod $period)
    {
        $curr = new DateTime();
        switch($period->getName())
            case PayPeriod::TYPE_WEEKLY: return $this->getLastWeekCutoff();
            case PayPeriod::TYPE_SEMIMONTHLY: return $this->getLastSemimonthlyCutoff();
        }
    }
    public function isSssScheduled(PayPeriod $period)
    {
        $curr = new DateTime();
        
        if($period->getName() == PayPeriod::TYPE_WEEKLY){

        }
    }
}