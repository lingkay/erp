<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Hris;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Location;

class LoadLocation extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */

	public function load(ObjectManager $em)
	{
		$user = $this->getReference('admin');
		$locations = ['7th Floor Ortigas','15th Floor Ortigas','Valenzuela',
						'Meycauyan, Bulacan','Cavite'];
		$address = ['Ortigas, Pasig City','Ortigas, Pasig City','Valenzuela','Bulacan','Cavite'];
		$x = 0;
		foreach($locations as $type)
		{
			$loc = new Location();
			$loc->setName($type)
				
				->setUserCreate($user);

			$em->persist($loc);
			$x++;
		}
		$em->flush();

	}

	public function getOrder()
	{
		return 4;
	}
}
?>