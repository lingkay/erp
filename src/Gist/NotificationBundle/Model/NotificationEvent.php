<?php
namespace Gist\NotificationBundle\Model;

use Symfony\Component\EventDispatcher\Event;
 
class NotificationEvent extends Event
{
    CONST EVENT = 'notification.event';
    private $data = array();
 
    public function notify($data)
    {
        $this->data = $data;
    }
 
    public function getData()
    {
        return $this->data;
    }
}