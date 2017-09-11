<?php

namespace Gist\InventoryBundle\Model;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use DirectoryIterator;

class Gallery
{
    protected $base_dir;
    protected $digit_pad;
    protected $allowed_exts;
    protected $id;
    protected $relative_dir;
    protected $directory;

    public function __construct($base_dir, $id, $digit_pad = 10)
    {
        $this->base_dir = $base_dir;
        $this->id = $id;
        $this->digit_pad = $digit_pad;
        $this->allowed_exts = array('png', 'jpg', 'jpeg', 'bmp', 'gif','pdf','doc','docx','xls','xlsx');

        $this->relative_dir = $this->getRelativeDirectory($id);
        $this->directory = $this->base_dir . DIRECTORY_SEPARATOR . $this->relative_dir;
    }

    public function setDigitPad($digit_pad)
    {
        // check divisible by 2
        if ($digit_pad % 2 == 0)
        {
            $this->digit_pad = $digit_pad;
            return $this;
        }

        // add 1 if not divisible by 2
        $this->digit_pad = $digit_pad + 1;

        return $this;
    }

    public function getRelativeDirectory($id)
    {
        $pad_id = sprintf('%0' . $this->digit_pad . 'd', $id);
        $dir_id = strrev($pad_id);

        // parse through reverse id string
        $count = 0;
        $num_dir = '';
        $dir_id_len = strlen($dir_id);
        for ($i = 0; $i < $dir_id_len; $i++)
        {
            $num_dir .= $dir_id[$i];
            $count++;
            if ($count == 2)
            {
                $num_dir .= DIRECTORY_SEPARATOR;
                $count = 0;
            }
        }

        return $num_dir;
    }

    public function addImage(UploadedFile $file)
    {
        // do checks
        if (!$this->checkImage($file))
            return false;

        // make directory
        $this->makeDirectory($this->directory);

        // generate filename
        $filename = $file->getClientOriginalName();

        // move file
        $file->move($this->directory, $filename);

        return true;
    }

    public function getImages()
    {
        $images = array();

        // directory exists?
        if (!file_exists($this->directory))
            return $images;

        // TODO: check if directory

        // get images in directory
        $dir = new DirectoryIterator($this->directory);
        foreach ($dir as $filemeta)
        {
            // skip directories
            if ($filemeta->isDir())
                continue;

            // check valid extension
            if (!$this->checkExtension($filemeta->getExtension()))
                continue;

            $images[] = $this->relative_dir . $filemeta->getFilename();
        }

        return $images;
    }

    public function checkImage(UploadedFile $file)
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

        // TODO: check dimensions

        return true;
    }

    protected function checkExtension($ext)
    {
        $ext = trim(strtolower($ext));
        foreach ($this->allowed_exts as $valid_ext)
        {
            if ($ext == $valid_ext)
                return true;
        }

        return false;
    }

    protected function makeDirectory($dir)
    {
        // first check if it already exists
        if (file_exists($dir))
            return false;

        mkdir($dir, 0755, true);

        return true;
    }

    protected function generateFilename(UploadedFile $file)
    {
        $id = uniqid();
        $ext = $file->getClientOriginalExtension();

        return $id . '.' . $ext;
    }
}
