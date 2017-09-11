<?php

namespace Gist\MediaBundle\Template\Entity;

use Gist\MediaBundle\Entity\Upload;

trait IsStorage
{
    /**
     * @ORM\OneToOne(
     *   targetEntity="Gist\MediaBundle\Entity\Upload",
     *   cascade={"persist"}
     * )
     * @ORM\JoinColumn(name="upload_id", referencedColumnName="id")
     */
    protected $upload;

    public function initIsStorage()
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
