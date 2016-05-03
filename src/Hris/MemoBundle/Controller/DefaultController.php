<?php

namespace Hris\MemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('HrisMemoBundle:Default:index.html.twig', array('name' => $name));
    }
}
