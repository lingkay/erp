<?php

namespace Gist\PayrollBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\PayrollBundle\Entity\PayPhilHealthRate;


class LoadPayPhilHealthRate extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        //Salary Bracket
        $salary_table = array(
            '1-8999.99'       => array(200.00,100.00,100.00),
            '9000.00-9999.99'     => array(225.00,112.50,112.50),
            '10000.00-10999.99'   => array(250.00,125.00,125.00),
            '11000.00-11999.99'   => array(275.00,137.50,137.50),
            '12000.00-12999.99'   => array(300.00,150.00,150.00),
            '13000.00-13999.99'   => array(325.00,162.50,162.50),
            '14000.00-14999.99'   => array(350.00,175.00,175.00),
            '15000.00-15999.99'   => array(375.00,187.50,187.50),
            '16000.00-16999.99'   => array(400.00,200.00,200.00),
            '17000.00-17999.99'   => array(425.00,212.50,212.50),
            '18000.00-18999.99'   => array(450.00,225.00,225.00),
            '19000.00-19999.99'   => array(475.00,237.50,237.50),
            '20000.00-20999.99'   => array(500.00,250.00,250.00),
            '21000.00-21999.99'   => array(525.00,262.50,262.50),
            '22000.00-22999.99'   => array(550.00,275.00,275.00),
            '23000.00-23999.99'   => array(575.00,287.50,287.50),
            '24000.00-24999.99'   => array(600.00,300.00,300.00),
            '25000.00-25999.99'   => array(625.00,312.50,312.50),
            '26000.00-26999.99'   => array(650.00,325.00,325.00),
            '27000.00-27999.99'   => array(675.00,337.50,337.50),
            '28000.00-28999.99'   => array(700.00,350.00,350.00),
            '29000.00-29999.99'   => array(725.00,362.50,362.50),
            '30000.00-30999.99'   => array(750.00,375.00,375.00),
            '31000.00-31999.99'   => array(775.00,387.50,387.50),
            '32000.00-32999.99'   => array(800.00,400.00,400.00),
            '33000.00-33999.99'   => array(825.00,412.50,412.50),
            '34000.00-34999.99'   => array(850.00,425.00,425.00),
            '35000.00-999999999'  => array(875.00,437.50,437.50),
        );

        foreach($salary_table as $key => $data){
            
            $salary = new PayPhilHealthRate();
            $salary->setBracket($key);

            $amt = explode('-',$key);
            
            $salary->setMinimum($amt[0]);
            $salary->setMaximum($amt[1]);
            
            $salary->setTotal($data[0]);
            $salary->setEmployer($data[1]);
            $salary->setEmployee($data[2]);
            
            $em->persist($salary);
        }

        $em->flush();
    }
    public function getOrder()
    {
        return 3;
    }
}