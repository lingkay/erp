<?php

namespace Catalyst\PayrollBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\PayrollBundle\Entity\PayPeriod;
use Hris\PayrollBundle\Entity\PaySchedule;


class LoadPayperiod extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        $payperiod = array(24 => 'Semi-Monthly',
                        12    => 'Monthly',
                        52    => 'Weekly',
                        104   => 'Bi-Weekly',
                        312   => 'Daily',
                        4     => 'Quarterly',
                        2     => 'Bi-Annual',
                        1     => 'Annual');
        foreach($payperiod as $key => $value){
            $pt = new PayPeriod();
            $pt->setPaydays($key)
               ->setName($value);

            $em->persist($pt);

            if($value == "Weekly"){
               
                  $startend = array("weekly_start"=> 0, "weekly_end"=> 5 ,"weekly_pay" => 1,
                                "monthly_start"=>28,"monthly_end"=>27, "monthly_pay"=>0,
                                    "semimonthly_start1"=>26,"semimonthly_end1"=>10,"semimonthly_pay1"=>15,
                                    "semimonthly_start2"=>11,"semimonthly_end2"=>25,"semimonthly_pay2"=>0 );
                   $paySchedule = new PaySchedule;
                $paySchedule->setName('Weekly')
                ->setUserCreate($user)
                ->setPeriod($pt)
                ->setStartEnd($startend);
                $em->persist($paySchedule);
                $em->flush();
            }

            if($value == "Semi-Monthly"){
                $startend = array("weekly_start"=> 0, "weekly_end"=> 5 ,"weekly_pay" => 1,
                                "monthly_start"=>28,"monthly_end"=>27, "monthly_pay"=>0,
                                    "semimonthly_start1"=>26,"semimonthly_end1"=>10,"semimonthly_pay1"=>15,
                                    "semimonthly_start2"=>11,"semimonthly_end2"=>25,"semimonthly_pay2"=>0 );
               
                $paySchedule = new PaySchedule;
                $paySchedule->setName('Semi-Monthly')
                ->setUserCreate($user)
                ->setPeriod($pt)
                ->setStartEnd($startend);
                //->setStartEnd('{"weekly_start":"0","weekly_end":"5","weekly_pay":"1","monthly_start":"0","monthly_end":"0","monthly_pay":"0","semimonthly_start1":"0","semimonthly_end1":"0","semimonthly_pay1":"0","semimonthly_start2":"0","semimonthly_end2":"0","semimonthly_pay2":"0"}');
               $em->persist($paySchedule);
                $em->flush();
            }
        }
        $em->flush();
    
    }
    
    public function getOrder()
    {
        return 2;
    }
}