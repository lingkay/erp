<?php

namespace Gist\ChartBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GistChartBundle:Default:index.html.twig', array('name' => $name));
    }
}
