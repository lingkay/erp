<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\WorldLocation;
use Symfony\Component\Finder\Finder;

class LoadLocation extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        //$em = $this->getDoctrine()->getManager();
        // Bundle to manage file and directories
        $finder = new Finder();
        $finder->in('sql');
        $finder->name('locations.sql');

        foreach( $finder as $file )
        {
            $content = $file->getContents();
            $stmt = $em->getConnection()->prepare($content);
            $stmt->execute();
        }
    
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 2;
    }
}
    
    
