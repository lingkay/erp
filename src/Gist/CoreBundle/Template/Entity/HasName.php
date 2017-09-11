<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasName
{
    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $name;

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function initHasName()
    {
        $this->name = '';
    }

    public function dataHasName($data)
    {
        $data->name = $this->name;
    }
}
