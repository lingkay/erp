<?php

namespace Gist\GalleryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('GistGalleryBundle:Default:index.html.twig', array('name' => $name));
    }
}
