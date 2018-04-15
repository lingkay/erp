<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\BonusTypes;

use DateTime;

class LoadBonusTypes extends AbstractFixture implements OrderedFixtureInterface
{

    /**
     * {@inheritDoc}
     */

    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');

        $types = \Hris\AdminBundle\Utility\BonusTypes::getBonusTypesOptions();


        foreach($types as $index => $data) {

            $bonusType = new BonusTypes();
            $bonusType->setName($data[0]);
            $em->persist($bonusType);
        }

        $em->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}