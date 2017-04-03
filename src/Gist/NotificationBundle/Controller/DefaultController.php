<?php

namespace Gist\NotificationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GistNotificationBundle:Default:index.html.twig', array('name' => $name));
    }
}
