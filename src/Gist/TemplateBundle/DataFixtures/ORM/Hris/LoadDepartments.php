<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Department;
use Hris\AdminBundle\Entity\JobTitle;


class LoadDepartmentData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $departments = [
            'Management' => array(
                "President/CEO",
                "VP Operations"
            ),
            'Human Resource' => array(
                "HR Employee Relations Officer",
                "HR Compensation and Benefits Assistant",
                "HR Recruitment Assistant"
            ),
            'Admin' => array(
                "Admin Officer-in-Charge",
                "Admin Officer",
                "Admin Assistant",
                "IT/Technical Support Officer",
                "Document Controller Specialist",
                "Plant Admin",
                "Janitor/Maintenance"
            ),
            'Accounting/Finance' => array(
                "Accounting Head",
                "Accounting Officer-in-Charge",
                "Accounting Assistant",
                "Account Receivable",
                "Account Receivable Staff",
                "Treasury Officer"
            ),
            'Marketing' => array(
                "Trade Marketing Specialist (Key Accounts)",
                "Trade Marketing Specialist (Distributor)",
                "Brand Marketing Officer",
                "Graphic Artist",
                "Business Development Manager"
            ),
            'Mechandising' => array(
                "Merchandising Office-in-Charge",
                "Merchandising Operations Supervisor",
                "Merchandising Support Assistant",
                "Sales Commando"
            ),
            'Sales' => array(
                "National Sales Manager",
                "Sales Admin Assistant",
                "Junior Sales Manager",
                "Distributor Sales Specialist",
                "Key Account Specialist",
                "Channel Sales Supervisor",
                "Senior Accounts Specialist"
            ),
            'Sales Monitoring' => array(
                "Sales Monitoring Officer-in-Charge",
                "Sales Monitoring Staff"
            ),
            'Purchasing' => array(
                "Purchasing Officer-in-Charge"
            ),
            'Logistic' => array(
                "Logistic Officer-in-Charge",
                "Logistic Assistant",
                "Logistic Staff",
                "Delivery Driver",
                "Delivery Helper"
            ),
            'Warehousing' => array(
                "Warehousing Supervisor",
                "Warehousing Assistant",
                "Warehouseman"
            ),
            'Research and Development' => array(
                "R&D Junior Manager",
                "R&D Assistant"
            ),
            'Quality Assurance' => array(
                "Quality Assurance Specialist",
                "Quality Assurance Assistant"
            ),
            'Production' => array(
                "Plant Operations Manager",
                "Production Officer",
                "Production Worker/Factory Worker"
            )
        ];


        foreach($departments as $dept => $jobs){
            $d = new Department();
            $d->setName($dept)
               ->setUserCreate($user);

            foreach ($jobs as $name) {
                $jt = new JobTitle();
                $jt->setName($name)
                    ->setDepartment($d)
                    ->setUserCreate($user);

                $em->persist($jt);
            }

            $em->persist($d);
        }
        $em->flush();
    
    }
    
    public function getOrder()
    {
        return 2;
    }
}