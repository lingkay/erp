<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Holiday;

use DateTime;

class LoadHoliday extends AbstractFixture implements OrderedFixtureInterface
{

	/**
	 * {@inheritDoc}
	 */

	public function load(ObjectManager $em)
	{
		$user = $this->getReference('admin');

        $holidays = array(
            array ('New Year\'s Day','01/01/2015',Holiday::TYPE_REGULAR),
            array('Additional special non-working day','01/02/2015', Holiday::TYPE_SPECIAL),
            array('Chinese New Year','02/19/2015',Holiday::TYPE_SPECIAL),
            array('Maundy Thursday','04/02/2015',Holiday::TYPE_REGULAR),
            array('Good Friday','04/03/2015',Holiday::TYPE_REGULAR),
            array('Black Saturday','04/04/2015',Holiday::TYPE_SPECIAL),
            array('Araw Ng Kagitingan','04/09/2015',Holiday::TYPE_REGULAR),
            array('Labor Day','05/01/2015',Holiday::TYPE_REGULAR),
            array('Independence Day','06/12/2015',Holiday::TYPE_REGULAR),
            array('Ninoy Aquino Day','08/21/2015',Holiday::TYPE_SPECIAL),
            array('National Heroes Day','08/31/2015',Holiday::TYPE_REGULAR),
            array('All Saints Day','11/01/2015',Holiday::TYPE_SPECIAL),
            array('Bonifacio Day','11/30/2015',Holiday::TYPE_REGULAR),
            array('Additional special non-working day','12/24/2015',Holiday::TYPE_SPECIAL),
            array('Christmas Day','12/25/2015',Holiday::TYPE_REGULAR),
            array('Rizal Day','12/30/2015',Holiday::TYPE_REGULAR),
            array('Last day of the year','12/31/2015',Holiday::TYPE_SPECIAL)
        );

        
        foreach($holidays as $index => $data) {
            
            $holiday = new Holiday();

            $date = new DateTime($data[1]);

            $holiday->setName($data[0])
                    ->setDate($date)
                    ->setType($data[2])
                    ->setUserCreate($user);

            $em->persist($holiday);
        }


		$em->flush();
	}

	public function getOrder()
	{
		return 2;
	}	
}