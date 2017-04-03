<?php

namespace Hris\AdminBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Hris\AdminBundle\Entity\WorldLocation;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;

class LoadTheme extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    protected $container;
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $em)
    {
        $conf = $this->container->get('gist_configuration');

        $conf->set('cat_color_header', '#000');
        $conf->set('cat_color_primary', '#0C4A08');
        $conf->set('cat_color_secondary','#1C6712');
        $conf->set('cat_color_tertiary','#428C38');
        $conf->set('cat_color_bg_image','#1c6712');
        $conf->set('cat_color_login','#428C38');
        $em->flush();
    }

    public function setContainer( ContainerInterface $container = null )
    {
        $this->container = $container;
    }

    public function getOrder()
    {
        return 1;
    }
}
    
    
