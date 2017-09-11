<?php
namespace Gist\NotificationBundle\Model;

use Doctrine\ORM\EntityManager;


class NotificationListener
{
	protected $manager;


    public function __construct($container)
    {
        $this->manager = $container->get('gist_notify');
    }

	public function onNotify(NotificationEvent $event)
    {
        $this->manager->notify($event->getData());
    }

}