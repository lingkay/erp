<?php

namespace Gist\MediaBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Doctrine\ORM\EntityManager;

interface StorageEngineInterface
{
    public function addFile(EntityManager $em, UploadedFile $file, $user);
}
