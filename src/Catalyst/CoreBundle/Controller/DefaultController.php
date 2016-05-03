<?php

namespace Catalyst\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalystCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
