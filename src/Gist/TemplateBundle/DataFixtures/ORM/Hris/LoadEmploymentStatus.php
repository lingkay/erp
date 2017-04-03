<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Hris;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
//use Hris\AdminBundle\Entity\EmploymentStatus;


class LoadEmploymentStatus extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        // $emp_status = ['Contractual', 'Probationary', 'Regular'];
        // foreach($emp_status as $type){
        //     $pt = new EmploymentStatus();
        //     $pt->setName($type)
        //        ->setUserCreate($user);
            
        //     $em->persist($pt);
        // }
        // $em->flush();
    
    }
    
    public function getOrder()
    {
        return 3;
    }
}