<?php

namespace Gist\TemplateBundle\DataFixtures\ORM\Hris;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\Schedules;
use DateTime;
class LoadSchedules extends AbstractFixture implements OrderedFixtureInterface
{
	/**
	 * {@inheritDoc}
	 */

	public function load(ObjectManager $em)
	{
		$user = $this->getReference('admin');
		$schedule = ['Merchandising, Sales and Marketing','Office Staff','Production', 'Delivery'];
		$start = ['2015-04-14 8:30','2015-04-14 9:00', '2015-04-14 7:00', '2015-04-14 6:00'];
		$end = ['2015-04-14 17:30','2015-04-14 18:00','2015-04-14 16:00','2015-04-14 15:00'];
		//
		$jobLevels = array('Managerial', 'Rank and File');
		$graceperiod = array('Manegerial'=>30, 'Rank and File'=>15);
		$halfday = 60;
		//
		$x = 0;
		foreach($schedule as $type)
		{
			foreach ($jobLevels as $jobLevel) 
			{
				$sched = new Schedules();
				$timestart = new DateTime($start[$x]);
				$timeend = new DateTime($end[$x]);
				switch ($jobLevel) {
	            case 'Rank and File':
	                $sched->setGracePeriod($graceperiod['Rank and File']);
	                break;
	            case 'Managerial':
	                $sched->setGracePeriod($graceperiod['Manegerial']);
	                break;
	        }

				
				$sched->setName($type.' - '.$jobLevel)
					  ->setStart($timestart)
					  ->setEnd($timeend)
					  ->setDayStart('Monday')
					  ->setDayEnd('Saturday')
					  ->setHalfday($halfday)
					  ->setUserCreate($user);

				$em->persist($sched);
				
			}
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