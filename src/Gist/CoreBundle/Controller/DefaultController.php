<?php

namespace Gist\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GistCoreBundle:Default:index.html.twig', array('name' => $name));
    }
}
