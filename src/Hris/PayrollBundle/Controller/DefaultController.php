<?php

namespace Hris\PayrollBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisPayrollBundle:Default:index.html.twig', array('name' => $name));
    }
}
