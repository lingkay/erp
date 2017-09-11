<?php
namespace Hris\NotificationBundle\Model;

use Doctrine\ORM\EntityManager;
use Gist\NotificationBundle\Model\NotificationEvent;

class NotificationListener
{
	protected $manager;


    public function __construct($container)
    {
        $this->manager = $container->get('hris_notify');
    }

	public function onNotify(NotificationEvent $event)
    {
        $this->manager->notify($event->getData());
    }

}