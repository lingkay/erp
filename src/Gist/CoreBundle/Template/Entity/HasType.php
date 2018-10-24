<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasType
{
    /** @ORM\Column(type="string", length=40, nullable=true) */
    protected $type;

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function initHasType()
    {
        $this->type = '';
    }

    public function dataHasType($data)
    {
        $data->type = $this->type;
    }
}
