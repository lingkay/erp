<?php

namespace Hris\RemunerationBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisRemunerationBundle:Default:index.html.twig', array('name' => $name));
    }
}
