<?php

namespace Gist\CustomerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('GistCustomerBundle:Default:index.html.twig');
    }
}
