<?php

namespace Gist\MediaBundle\Template\Entity;

use Gist\MediaBundle\Entity\Upload;

trait HasUpload
{
    /**
     * @ORM\OneToOne(targetEntity="Gist\MediaBundle\Entity\Upload",  fetch="EXTRA_LAZY")
     * @ORM\JoinColumn(name="upload_id", referencedColumnName="id")
     */
    protected $upload;

    public function initIsUpload()
    {
    }

    public function setUpload(Upload $upload)
    {
        $this->upload = $upload;
        return $this;
    }

    public function getUpload()
    {
        return $this->upload;
    }
}
