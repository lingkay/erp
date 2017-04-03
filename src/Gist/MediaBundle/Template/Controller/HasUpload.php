<?php

namespace Gist\MediaBundle\Template\Controller;

trait HasUpload
{
    protected function getUploadManager()
    {
    }

    protected function updateHasUpload($o, $data, $is_new)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $file = $this->getRequest()->files->get($param)
    }
}
