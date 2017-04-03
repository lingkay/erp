<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Hris;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Leave\LeaveType;

class LoadLeaves extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */

    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $leaves = array(
            'Service Incentive Leave' => array(
                    'desc' => 'Government-mandated leave for employees that have rendered at least 1 year of service in the company',
                    'leave_count' => 5,
                    'count_type' => 'per Year',
                    'service_months' => 12,
                    'payment_type' => 'FULL',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Male' => 'Male','Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular']
                ),
            'Sick Leave' => array(
                    'desc' => '12 days of Leave for employees that are sick',
                    'leave_count' => 12,
                    'count_type' => 'per Year',
                    'service_months' => 12,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 1,
                    'accrue_time' => 1,
                    'accrue_rule' => 'Yearly',
                    'carried_enabled' => 1,
                    'carry_percentage' => 100,
                    'carry_period' => 8,
                    'gender' => ['Male' => 'Male','Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular']
                ),
            'Paternity Leave' => array(
                    'desc' => '7 days of leave for Male employees during Birth or Miscarriage of Legitimate Spouse',
                    'leave_count' => 7,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Male' => 'Male'],
                    'emp_status' => ['Regular' => 'Regular']
                ),
            'Bereavement' => array(
                    'desc' => '3 days of leave in the event of death of a family member.',
                    'leave_count' => 3,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Male' => 'Male', 'Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
            'Solo Parent Leave' => array(
                    'desc' => 'Persons who fall under the definition of solo parents and who have rendered service of at least one year are entitled to 7 working days of leave to attend to their parental duties.',
                    'leave_count' => 7,
                    'count_type' => 'per Year',
                    'service_months' => 12,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Male' => 'Male', 'Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
            'Calamity Leave' => array(
                    'desc' => '2 days of leave for employees that are directly affected by calamity (e.g. Fire, Flood, Typhoon)',
                    'leave_count' => 2,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Male' => 'Male', 'Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
            'Magna Carta for Women' => array(
                    'desc' => 'Leave for female employees who underwent surgery caused by gynecological disorders',
                    'leave_count' => 60,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'NONE',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
            'Maternity Leave (Normal Delivery)' => array(
                    'desc' => '',
                    'leave_count' => 60,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'FULL',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
            'Maternity Leave (Caesarian)' => array(
                    'desc' => '',
                    'leave_count' => 78,
                    'count_type' => 'per Request',
                    'service_months' => 6,
                    'payment_type' => 'FULL',
                    'accrued_enabled' => 0,
                    'accrue_time' => NULL,
                    'accrue_rule' => NULL,
                    'carried_enabled' => 0,
                    'carry_percentage' => NULL,
                    'carry_period' => NULL,
                    'gender' => ['Female' => 'Female'],
                    'emp_status' => ['Regular' => 'Regular', 'Probationary' => 'Probationary']
                ),
        );

        // "Probationary"
        // "Contractual"
        // "Regular"

        // 'Weekly'
        // 'Monthly'
        // 'Quarterly'
        // 'Yearly'

        $emp_status = [1 => 'Probationary',3 => 'Regular'];
        foreach($leaves as $leave => $data)
        {
            $o = new LeaveType();
            $o->setGender($data['gender']);
            $o->setEmpStatus($data['emp_status']);
            $o->setName($leave);
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
        }
        
        $em->flush();
    }


    public function getOrder()
    {
        return 4;
    }
}
?>