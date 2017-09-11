<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Hris;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Benefit;

class LoadBenefits extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */

    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $benefits = [
            'SSS' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'PhilHealth' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'PAG-IBIG' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'Sick Leave' => [Benefit::TYPE_DAYS,['Male','Female']],
            'Vacation Leave' => [Benefit::TYPE_DAYS,['Male','Female']],
            'Maternity Leave' => [Benefit::TYPE_DAYS,[1=>'Female']],
            'Paternity Leave' => [Benefit::TYPE_DAYS,['Male']],
            'Parental Leave' => [Benefit::TYPE_DAYS,['Male','Female']],
            'Maternity Leave' => [Benefit::TYPE_DAYS,[1=>'Female']],
            'Leave for VAWC' => [Benefit::TYPE_DAYS,[1=>'Female']],
            'Special leave for women ' => [Benefit::TYPE_DAYS,['Female']],
            '13th month Pay' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'Separation Pay' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'ECOLA' => [Benefit::TYPE_MONEY, ['Male','Female']],
            'Savings' => [Benefit::TYPE_MONEY, ['Male','Female']]
        ];

        $emp_status = [1 => 'Probationary',3 => 'Regular'];
        foreach($benefits as $ben => $type)
        {
            $b = new Benefit();
            $b->setName($ben)
                ->setType($type[0])
                ->setEmpStatus($emp_status)
                ->setGender($type[1])
                ->setUserCreate($user);

            $em->persist($b);
        }
        
        $em->flush();
    }

    public function getOrder()
    {
        return 4;
    }
}
?>