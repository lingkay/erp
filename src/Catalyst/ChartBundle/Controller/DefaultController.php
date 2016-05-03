<?php

namespace Catalyst\ChartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('CatalystChartBundle:Default:index.html.twig', array('name' => $name));
    }
}
