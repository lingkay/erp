<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasStatus
{
    /** @ORM\Column(type="string", length=40, nullable=true) */
    protected $status;

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function initHaStatus()
    {
        $this->status = '';
    }

    public function dataHasStatus($data)
    {
        $data->status = $this->status;
    }
}
