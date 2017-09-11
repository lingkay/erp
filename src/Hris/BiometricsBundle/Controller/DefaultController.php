<?php

namespace Hris\BiometricsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisBiometricsBundle:Default:index.html.twig', array('name' => $name));
    }
}
