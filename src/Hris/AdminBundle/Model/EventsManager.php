<?php

namespace Hris\AdminBundle\Model;

use Doctrine\ORM\EntityManager;

use Gist\NotificationBundle\Entity\Notification;
use Gist\NotificationBundle\Model\NotificationEvent;
use DateTime;

class EventsManager
{
    protected $em;
    protected $container;


    public function __construct(EntityManager $em,$container = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function checkForEventsTomorrow()
    {
        $em = $this->em;
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $date_tmr = date('Y-m-d',strtotime("+1 days"));
        $events = $em->getRepository("HrisAdminBundle:Events")->createQueryBuilder('o')
           ->where('o.date_from LIKE :name')
           ->setParameter('name', $date_tmr."%")
           ->getQuery()
           ->getResult();

        foreach ($events as $ev) 
        {
            //CREATE NOTIFICATION 
            $event = new NotificationEvent();
            $notif_body = array('type'=> Notification::TYPE_UPDATE);
            $notif_body['link'] = '/notifications';
            $notif_body['message']      = $ev->getName().' will be tomorrow.';
            $notif_body['source']       = 'Event Notification';
            $event->notify($notif_body);
            $dispatcher         = $this->container->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
         }
    }

    public function checkForBirthdaysTomorrow($hr)
    {
        $em = $this->em;
        $today = new DateTime();
        $today = $today->format('Y-m-d');
        $date_tmr = date('m-d',strtotime("+1 days"));
        $events = $em->getRepository("HrisWorkforceBundle:Profile")->createQueryBuilder('o')
           //  ->where('o.birthday >= :today AND o.birthday <= :today')
           // ->setParameter('today', $today)
           ->getQuery()
           ->getResult();

        foreach ($events as $ev) 
        {
            if ($ev->getBirthday() != null) 
            {
              
                if ($ev->getBirthday()->format('m-d') == $date_tmr) 
                {
                    //CREATE NOTIFICATION 
                    $event = new NotificationEvent();
                    $notif_body = array('type'=> Notification::TYPE_UPDATE);
                    $notif_body['link'] = '/notifications';
                    $notif_body['message']      = $ev->getEmployee()->getDisplayName().'\'s birthday will be tomorrow.';
                    $notif_body['source']       = 'Birthday Notification';
                    $notif_body['receipient']   = $hr;
                    $event->notify($notif_body);
                    $dispatcher = $this->container->get('event_dispatcher');
                    $dispatcher->dispatch('notification.event', $event);
                }
            }
         }
    }
}