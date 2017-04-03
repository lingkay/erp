<?php

namespace Gist\NotificationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\NotificationBundle\Entity\Notification;
use Gist\UserBundle\Entity\User;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="ntf_notification_queue")
 */
class NotificationQueue
{
    use HasGeneratedID;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\NotificationBundle\Entity\Notification")
     * @ORM\JoinColumn(name="notification_id", referencedColumnName="id")
     */
    protected $notification;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /** @ORM\Column(type="boolean") */
    protected $flag_read;


    public function __construct(Notification $notification,User $receipient){
        $this->notification = $notification;
        $this->user = $receipient;
    	$this->flag_read = false;
    }

    public function setNotification(Notification $notification)
    {
        $this->notification = $notification;
        return $this;
    }

    public function getNotification()
    {
        return $this->notification;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setRead()
    {
        $this->flag_read = true;
    }

    public function isRead()
    {
        return $this->flag_read;
    }

	public function toData()
    {
        $data = new stdClass();

        $this->dataHasGeneratedID($data);

        return $data;
    }

}