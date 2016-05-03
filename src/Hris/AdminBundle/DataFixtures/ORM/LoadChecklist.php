<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Checklist;


class LoadChecklist extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $departments = ['NBI CLearance', 'Police Clearance', 'Brgy Clearance',
                         'Medical','Drug Test','NSO Birth Certificate',
                         'Birth Certificate of Dependents','2x2 Picture', 'Photocopy of SSS No','Photocopy of Tin No',
                         'Photocopy of Philhealth No', 'Photocopy of Pag-ibig No', 'Photocopy of Diploma',
                         'BIR 2305 w/ stamp','1905 w/ stamp','1902 w/ stamp', 'PMRF','MDF','SSS loan verification',
                         'Photocopy of Certificate of Employement (COE)','Photocopy of Clearance','2316 of current year'];
        foreach($departments as $type){
            $pt = new Checklist();
            $pt->setName($type)
                ->setNotes('')
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