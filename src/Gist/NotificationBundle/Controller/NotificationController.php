<?php

namespace Gist\NotificationBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;

class NotificationController extends BaseController
{

    public function notificationsAction()
    {
    	$service = $this->get('gist_notify');
        $notifications = $service->getNotifications($this->getUser());
        $notification_arr = array();
        $unread = 0;
        foreach ($notifications as $notification) {
            $ntf['message'] = $notification->getNotification()->getMessage();
            $ntf['id'] = $notification->getID();
            $ntf['read'] = $notification->isRead();
            $ntf['type'] = $notification->getNotification()->getType();
            $ntf['link'] = $notification->getNotification()->getLink();
            $ntf['time_passed'] = $notification->getNotification()->getTimePassed();
            $unread = $notification->isRead() == false? $unread+1: $unread;
            $notification_arr[] = $ntf;
        }
        $response['notifications'] = $notification_arr; 
        $response['unread'] = $unread;

        return new JsonResponse($response);
    }

    public function indexAction()
    {
        $this->title = 'Notifications';

        $service = $this->get('gist_notify');
        $params = $this->getViewParams('List');

        $params['notifications'] = $service->getNotifications($this->getUser(), 50);

        return $this->render('GistNotificationBundle:Notification:index.html.twig', $params);
    }

    public function setReadAction($id)
    {
        $service = $this->get('gist_notify');
        $em = $this->getDoctrine()->getManager();
        $notification = $service->findNotification($id);
        $notification->setRead();
        $em->persist($notification);
        $em->flush();

        $response = array(true);
        return new JsonResponse($response);
    }
}