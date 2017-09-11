<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasCode
{
    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $code;

    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function initHasCode()
    {
        $this->code = '';
    }

    public function dataHasCode($data)
    {
        $data->code = $this->code;
    }
}
