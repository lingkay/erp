<?php

namespace Hris\WorkforceBundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

use Hris\PayrollBundle\Entity\PayTaxStatus;

class LoadTaxStatus extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        $tax_status = ['Z','ME/S', 'ME1/S1', 'ME2/S2', 'ME3/S3','ME4/S4'];
        
            foreach ($tax_status as $status) {
                
                $taxstatus = new PayTaxStatus();
                
                switch ($status) {
                    case 'Z':
                        $taxstatus->setPersonal(0);
                        $taxstatus->setAdditional(0);
                        $taxstatus->setTotal(0);
                        break;
                    case 'ME/S':
                        $taxstatus->setPersonal(50000);
                        $taxstatus->setAdditional(0);
                        $taxstatus->setTotal(50000);
                        break;
                    case 'ME1/S1':
                        $taxstatus->setPersonal(50000);
                        $taxstatus->setAdditional(25000);
                        $taxstatus->setTotal(75000);
                        break;
                    case 'ME2/S2':
                        $taxstatus->setPersonal(50000);
                        $taxstatus->setAdditional(50000);
                        $taxstatus->setTotal(100000);
                        break;
                    case 'ME3/S3':
                        $taxstatus->setPersonal(50000);
                        $taxstatus->setAdditional(75000);
                        $taxstatus->setTotal(125000);
                        break;
                    case 'ME4/S4':
                        $taxstatus->setPersonal(50000);
                        $taxstatus->setAdditional(100000);
                        $taxstatus->setTotal(150000);
                        break;
                }
                $taxstatus->setCode($status);
                
                $em->persist($taxstatus);
            }
        $em->flush();
    }
    
    public function getOrder()
    {
        return 2;
    }
}