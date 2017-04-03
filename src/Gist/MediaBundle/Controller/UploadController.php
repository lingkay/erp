<?php

namespace Gist\MediaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class UploadController extends Controller
{
    public function uploadAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_media');

        $file = $this->getRequest()->files->get('file');
        $um->enableExtensionCheck();
        $um->allowImageExtensions();
        $upload = $um->addFile($file);

        if ($upload == null)
        {
            $res = array('message' => 'Invalid Extension');
            return new JsonResponse($res);
        }

        $res = array(
            'id' => $upload->getID(),
            'filename' => $upload->getFilename(),
            'url' => $upload->getURL(),
            'filetype' => $upload->getFileType()
        );
        return new JsonResponse($res);
    }

    public function uploadDocAction()
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_media');

        $file = $this->getRequest()->files->get('file');

        $um->enableExtensionCheck();
        $um->allowDocsExtensions();
        $upload = $um->addFile($file);

        if ($upload == null)
        {
            $res = array('message' => 'Invalid Extension');
            return new JsonResponse($res);
        }

        $res = array(
            'id' => $upload->getID(),
            'filename' => $upload->getFilename(),
            'url' => $upload->getURL(),
            'filetype' => $upload->getFileType()
        );
        return new JsonResponse($res);
    }

    public function indexAction($name)
    {
        return $this->render('GistMediaBundle:Default:index.html.twig', array('name' => $name));
    }
}
