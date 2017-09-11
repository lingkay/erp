<?php

namespace Gist\MediaBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Gist\MediaBundle\Model\StorageEngineFactory;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use DirectoryIterator;
use Exception;

class UploadManager
{
    // storage engine
    protected $storage_engine;

    // whitelist of extensions
    protected $allowed_exts;

    // blacklist of extensions
    protected $blocked_exts;

    // if we check extension whitelist or not
    protected $flag_check_extension;

    protected $user;
    protected $em;

    public function __construct($storage_config, TokenStorage $token_storage, EntityManager $em)
    {
        $sef = new StorageEngineFactory();
        $this->storage_engine = $sef->getStorageEngine($storage_config);
        $this->user = $token_storage->getToken()->getUser();
        $this->em = $em;

        // extension checks
        $this->allowed_exts = array();
        $this->initBlockedExtensions();
        $this->flag_check_extension = false;
    }

    public function getUpload($id)
    {
        return $this->em->getRepository('GistMediaBundle:Upload')->find($id);
    }

    protected function initBlockedExtensions()
    {
        $this->blocked_exts = array(
            // php-related
            'php' => true,
            'php5' => true,
            'php4' => true,
            'php3' => true,
            'phps' => true,
            'phtml' => true,

            // windows executables
            'exe' => true,
            'dll' => true,
            'msi' => true,
            'bat' => true,
            'vbs' => true,
            'com' => true,
            'cmd' => true,
            'pif' => true,
            'vxd' => true,
            'cpl' => true,
            'scr' => true,

            // html
            'html' => true,
            'htm' => true,
            'js' => true,
            'jsb' => true,
            'mhtml' => true,
            'mht' => true,
            'xhtml' => true,
            'xht' => true,

            // other scripts
            'pl' => true,
            'py' => true,
            'cgi' => true,
            'jhtml' => true,
            'shtml' => true,
        );
    }

    public function allowImageExtensions()
    {
        $image_exts = array('png', 'jpg', 'jpeg', 'bmp', 'gif');
        $this->addAllowedExtensions($image_exts);

        return $this;
    }

    public function allowDocsExtensions()
    {
        $docs_exts = array('pdf', 'doc', 'docx', 'ppt', 'pptx',
            'pps', 'ppsx', 'odt', 'xls', 'xlsx');
        $this->addAllowedExtensions($docs_exts);

        return $this;
    }

    public function addAllowedExtensions($exts = array())
    {
        // update extension hash
        foreach ($exts as $ex)
        {
            $lower_ex = strtolower($ex);
            $this->allowed_exts[$lower_ex] = true;
        }

        return $this;
    }

    public function disableExtensionCheck()
    {
        $this->flag_check_extension = false;

        return $this;
    }

    public function enableExtensionCheck()
    {
        $this->flag_check_extension = true;

        return $this;
    }

    public function addFile(UploadedFile $file)
    {
        // do checks
        if (!$this->checkFile($file))
            return null;

        // save in storage engine
        return $this->storage_engine->addFile($this->em, $file, $this->user);
    }

    public function checkFile(UploadedFile $file)
    {
        // valid?
        if (!$file->isValid())
        {
            error_log('Not a valid file upload.');
            return false;
        }

        // check allowed extensions
        $ext = $file->getClientOriginalExtension();
        if (!$this->checkExtension($ext))
        {
            error_log($ext . ' is not a valid extension.');
            return false;
        }

        // TODO: check size

        return true;
    }

    protected function checkExtension($ext)
    {
        // if extension check is disabled
        if (!$this->flag_check_extension)
            return true;

        // check
        $lower_ex = trim(strtolower($ext));
        if (isset($this->allowed_exts[$lower_ex]) && $this->allowed_exts[$lower_ex])
            return true;

        return false;
    }
}
