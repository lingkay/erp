<?php

namespace Hris\WorkforceBundle\DataFixtures\ORM\Test;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\WorkforceBundle\Entity\Rating;


class LoadRatingSystem extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $user = $this->getReference('admin');
        $qualitative = [
            'Excellent',
            'Very Satisfactory',
            'Satisfactory',
            'Needs Improvement',
            'Poor'
        ];

        $rate_end = [100,89,79,59,39];
        $rate_start = [90,80,60,40,20];

        $desc = [
            "Exceptional Performance in all areas or responsibility. Planned objectives were achieved well above the established standards and accomplishments were made in unexpected areas.",
            "Consistently exceed established standards in most areas of responsibility. All requirements were met and objectives were achieved above the established standards.",
            "All job requirements were met and planned objectives were accomplished within established standards. There were no critical areas where accomplishments were less than planned.",
            "Performance in one or more critical areas does not meet expectations. Not all planned objectives were accomplished within the established standards and some responsibilities were not completely met.",
            "Does not meet minimum job requirements. Performance is unaaceptable. Responsibilities are not being met and important objectives have not been accomplished. Needs immediate improvement."
        ];

        foreach ($qualitative as $id => $rating) {
            $rt = new Rating();
            $rt->setRating($rating)
               ->setRangeStart($rate_start[$id])
               ->setRangeEnd($rate_end[$id])
               ->setDescription($desc[$id]);
            
            $em->persist($rt);
        }
        $em->flush();
    
    }
    
    public function getOrder()
    {
        return 2;
    }
}