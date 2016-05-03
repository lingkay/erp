<?php

namespace Catalyst\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalystNotificationBundle:Default:index.html.twig', array('name' => $name));
    }
}
