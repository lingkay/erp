<?php

namespace Catalyst\PayrollBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\PayrollBundle\Entity\PaySSSRate;


class LoadPaySSSRate extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        //Salary Bracket
        $salary_table = array(
            '1000.00-1249.99'    => array(1000.00,83.70,36.30,120.00),
            '1250.00-1749.99'    => array(1500.00,120.50,54.50,175.00),
            '1750.00-2249.99'    => array(2000.00,157.30,72.70,230.00),
            '2250.00-2749.99'    => array(2500.00,194.20,90.80,285.00),
            '2750.00-3249.99'    => array(3000.00,231.00,109.00,340.00),
            '3250.00-3749.99'    => array(3500.00,267.80,127.20,395.00),
            '3750.00-4249.99'    => array(4000.00,304.70,145.30,450.00),
            '4250.00-4749.99'    => array(4500.00,341.50,163.50,505.00),
            '4750.00-5249.99'    => array(5000.00,378.30,181.70,560.00),
            '5250.00-5749.99'    => array(5500.00,415.20,199.80,615.00),
            '5750.00-6249.99'    => array(6000.00,452.00,218.00,670.00),
            '6250.00-6749.99'    => array(6500.00,488.80,236.20,725.00),
            '6750.00-7249.99'    => array(7000.00,525.70,254.30,780.00),
            '7250.00-7749.99'    => array(7500.00,562.50,272.50,835.00),
            '7750.00-8249.99'    => array(8000.00,599.30,290.70,890.00),
            '8250.00-8749.99'    => array(8500.00,636.20,308.80,945.00),
            '8750.00-9249.99'    => array(9000.00,673.00,327.00,1000.00),
            '9250.00-9749.99'    => array(9500.00,709.80,345.20,1055.00),
            '9750.00-10249.99'   => array(10000.00,746.70,363.30,1110.00),
            '10250.00-10749.99'  => array(10500.00,783.50,381.50,1165.00),
            '10750.00-11249.99'  => array(11000.00,820.30,399.70,1220.00),
            '11250.00-11749.99'  => array(11500.00,857.20,417.80,1275.00),
            '11750.00-12249.99'  => array(12000.00,894.00,436.00,1330.00),
            '12250.00-12749.99'  => array(12500.00,930.80,454.20,1385.00),
            '12750.00-13249.99'  => array(13000.00,967.70,472.30,1440.00),
            '13250.00-13749.99'  => array(13500.00,1004.50,490.50,1495.00),
            '13750.00-14249.99'  => array(14000.00,1041.30,508.70,1550.00),
            '14250.00-14749.99'  => array(14500.00,1078.20,526.80,1605.00),
            '14750.00-15249.99'  => array(15000.00,1135.00,545.00,1680.00),
            '15250.00-15749.99'  => array(15500.00,1171.80,563.20,1735.00),
            '15750.00-999999999'      => array(16000.00,1208.70,581.30,1790.00)
        );

        foreach($salary_table as $key => $data){
            
            $salary = new PaySSSRate();
            $salary->setBracket($key);

            $amt = explode('-',$key);
            
            $salary->setMinimum($amt[0]);
            $salary->setMaximum($amt[1]);
            
            $salary->setSalaryCredit($data[0]);
            $salary->setEmployer($data[1]);
            $salary->setEmployeeContribution($data[2]);
            $salary->setTotal($data[3]);

            $em->persist($salary);
        }

        $em->flush();
    }
    public function getOrder()
    {
        return 3;
    }
}