<?php
namespace Gist\NotificationBundle\Model;

use Doctrine\ORM\EntityManager;
use Gist\NotificationBundle\Entity\Notification;
use Gist\NotificationBundle\Entity\NotificationQueue;
use Gist\UserBundle\Entity\User;

class NotificationManager
{
	protected $em;
	protected $user;
    protected $mailer;
    protected $templating;

	public function __construct(EntityManager $em, $container, $securityContext = null)
    {
        $this->em = $em;
        $this->user = $securityContext->getToken()->getUser();
        $this->mailer = $container->get('mailer');
        $this->templating = $container->get('templating');
    }


    public function newNotification($source, $link, $message, $type)
    {
    	$notification = new Notification($source, $link, $message, $type);
    	$this->em->persist($notification);
    	$this->em->flush();

        return $notification;
    }

    public function newNotificationQueue($notification , $user)
    {
        return new NotificationQueue($notification, $user);
    }

    public function notify($data)
    {
         $notification = $this->newNotification($data['source'], $data['link'], $data['message'], $data['type']);

        if(isset($data['receipient'])){
            $this->sendToUsers(array($data['receipient']));
        }else {
            $this->sendToAll($notification);
        }
    }

    protected function sendToAll($notification)
    {
        $users = $this->em->getRepository("GistUserBundle:User")->findByEnabled(true);
        $this->sendToUsers($notification, $users);
    }

    protected function sendToUsers($notification, $users)
    {
        foreach($users as $user){
            $queue = $this->newNotificationQueue($notification, $user);
            $this->em->persist($queue);
            $this->emailNotification($user, $notification);
        }
        $this->em->flush();
    }

    protected function emailNotification($user, $notification)
    {
        if($user->isEmailNotify())
        {
            try{
            $message = $this->mailer->createMessage()
                ->setSubject('HRIS Notification: '.$notification->getMessage())
                ->setFrom('developer@quadrantalpha.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->templating->render(
                        'GistNotificationBundle:Notification:notification_email.html.twig',
                        array('name'=> $user->getName(),
                               'notification' => $notification,
                               'company_name' => "HRIS"
                            // 'username' => $user->getUsername(),
                            // 'password'=>$user->getPlainPassword(),
                            // 'company_name'=>$company_name,
                            // 'company_address'=>$company_address,
                            // 'company_website'=>$company_website,
                            // 'logo'=>$logo
                            )
                    ),
                    'text/html'
                );
            
                $response = $this->mailer->send($message);
                error_log($response);
            }catch(\Swift_TransportException $e)
            {
                error_log($e->getMessage());
            }
        }
    }


    public function getNotifications($user, $limit = 50 )
    {
        return $this->em->getRepository('GistNotificationBundle:NotificationQueue')
            ->findBy(array('user'=>$user),array('id' => 'DESC'),$limit);
    }

    public function findNotification($id)
    {
        return $this->em->getRepository('GistNotificationBundle:NotificationQueue')->find($id);
    }
}