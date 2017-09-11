<?php
namespace Hris\NotificationBundle\Model;

use Doctrine\ORM\EntityManager;

use Gist\NotificationBundle\Entity\Notification;
use Gist\NotificationBundle\Entity\NotificationQueue;
use Gist\NotificationBundle\Model\NotificationManager as Manager;

use Hris\AdminBundle\Entity\Department;
use Hris\WorkforceBundle\Entity\Employee;

class NotificationManager extends Manager
{
	protected $em;
	protected $user;
    protected $mailer;
    protected $templating;

	public function __construct(EntityManager $em, $container, $securityContext = null)
    {
        $this->em = $em;
        $this->user = $securityContext->getToken() != NULL ? $securityContext->getToken()->getUser() : '';
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('templating');
    }

    public function notify($data)
    {
        $notification = $this->newNotification($data['source'], $data['link'], $data['message'], $data['type']);

        if(isset($data['receipient']))
        {
            $receipient = $data['receipient'];
            switch(true)
            {
                case $data['receipient'] instanceof Employee :
                    $this->sendToEmployee($notification, $receipient);
                    break;
                case $data['receipient'] instanceof Department :
                    $this->sendToDepartment($notification, $receipient);
                    break;
            }
        }else {
            $this->sendToAll($notification);
        }
    }

    protected function sendToEmployee($notification, $receipient)
    {
        $user = $receipient->getUser();
        $this->sendToUsers($notification, array($user));
    }

    protected function sendToDepartment($notification, $receipient)
    {
        $employees = $this->em->getRepository("HrisWorkforceBundle:Employee")->findByDepartment($receipient);
        $users = array();
        foreach($employees as $employee)
        {
            $users[] = $employee->getUser();
        }

        $this->sendToUsers($notification, $users);
    }

}