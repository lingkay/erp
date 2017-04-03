<?php

namespace Gist\MediaBundle\Model\StorageEngine;

use Gist\MediaBundle\Model\StorageEngineInterface;
use Gist\MediaBundle\Entity\Upload;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gist\MediaBundle\Entity\Storage\LocalFile as StorageLocalFile;
use Doctrine\ORM\EntityManager;

class LocalFile implements StorageEngineInterface
{
    protected $base_dir;
    protected $base_url;
    protected $directory;

    public function __construct($base_dir, $base_url)
    {
        $this->base_dir = $base_dir;
        $this->base_url = $base_url;

        // indexed directory
        $this->directory = '';
    }

    public function addFile(EntityManager $em, UploadedFile $file, $user)
    {
        // generate filename
        $filename = $this->generateFilename($file);
        $fullpath = $this->base_dir . DIRECTORY_SEPARATOR . $this->directory;

        // make directory
        $this->makeDirectory($fullpath);

        // move file
        $file->move($fullpath, $filename);

        // upload entity
        $upload = new Upload();
        $upload->setURL($this->base_url . '/' . $this->directory . '/' . $filename)
            ->setFilename($filename)
            ->setStorageType('local_file')
            ->setUserCreate($user);

        // storage entity
        $local_file = new StorageLocalFile();
        $local_file->setFullPath($fullpath . DIRECTORY_SEPARATOR . $this->directory . DIRECTORY_SEPARATOR . $filename)
            ->setUpload($upload);

        $em->persist($upload);
        $em->persist($local_file);
        $em->flush();

        return $upload;
    }

    protected function makeDirectory($dir)
    {
        // first check if it already exists
        if (file_exists($dir))
            return false;

        if (! mkdir($dir, 0755, true))
            throw new Exception('Could not create directory for upload.');

        return true;
    }

    protected function generateDirectory($id)
    {
        // single level
        $this->directory = substr($id, -2) . DIRECTORY_SEPARATOR . substr($id, -4, 2);

        return $this->directory;
    }

    protected function generateFilename(UploadedFile $file)
    {
        // generate unique id
        $id = uniqid();

        // generate relative directory
        $this->generateDirectory($id);

        // get extension
        $ext = $file->getClientOriginalExtension();

        return $id . '.' . $ext;
    }
}
