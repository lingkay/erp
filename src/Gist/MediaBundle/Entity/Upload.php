<?php

namespace Gist\MediaBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_upload")
 */
class Upload
{
    use HasGeneratedID;
    use TrackCreate;

    const STATUS_NEW            = 1;
    const STATUS_LINKED         = 2;

    /** @ORM\Column(type="string", length=200, nullable=false) */
    protected $filename;

    /** @ORM\Column(type="string", length=200, nullable=false) */
    protected $url;

    /** @ORM\Column(type="integer") */
    protected $status;

    /** @ORM\Column(type="string", length=30, nullable=false) */
    protected $storage_type;

    // storage engines
    /**
     * @ORM\OneToOne(targetEntity="Gist\MediaBundle\Entity\Storage\LocalFile", mappedBy="upload", cascade={"persist"})
     */
    protected $local_file;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->status = self::STATUS_NEW;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
        return $this;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setURL($url)
    {
        $this->url = $url;
        return $this;
    }

    public function getURL()
    {
        return $this->url;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getFileType()
    {
        $file = explode('.', $this->filename);
        return array_pop($file);
    }

    public function setStorageType($type)
    {
        $this->storage_type = $type;
        return $this;
    }

    public function getStorageType()
    {
        return $this->storage_type;
    }

    public function getStorage()
    {
        switch ($this->storage_type)
        {
            case 'local_file':
                return $this->local_file;
        }

        return null;
    }
}
