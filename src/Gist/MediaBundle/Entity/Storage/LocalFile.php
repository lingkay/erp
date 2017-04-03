<?php

namespace Gist\MediaBundle\Entity\Storage;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\MediaBundle\Template\Entity\IsStorage;

/**
 * @ORM\Entity
 * @ORM\Table(name="media_storage_localfile")
 */
class LocalFile
{
    use HasGeneratedID;
    use IsStorage;

    /** @ORM\Column(type="string", length=200, nullable=false) */
    protected $full_path;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initIsStorage();
    }

    public function setFullPath($fp)
    {
        $this->full_path = $fp;
        return $this;
    }

    public function getFullPath()
    {
        return $this->full_path;
    }
}
