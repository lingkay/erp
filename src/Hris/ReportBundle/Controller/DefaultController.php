<?php

namespace Hris\ReportBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisReportBundle:Default:index.html.twig', array('name' => $name));
    }
}
