<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\JobLevel;


class LoadJobLevel extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $departments = ['Rank and File', 'Officer', 'Managerial', 'Executive'];
        foreach($departments as $type){
            $pt = new JobLevel();
            $pt->setName($type)
               ->setUserCreate($user);
            
            $em->persist($pt);
        }
        $em->flush();
    
    }
    
    public function getOrder()
    {
        return 2;
    }
}
