<?php

namespace Gist\InventoryBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;
use Gist\UserBundle\Entity\User;

class LoadInventorySettings extends AbstractFixture implements OrderedFixtureInterface
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
        $finder->name('inv_settings.sql');

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
        return 100;
    }
}
    
    
