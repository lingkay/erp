<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasNotes
{
    /** @ORM\Column(type="text", nullable=true) */
    protected $notes;

    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    public function getNotes()
    {
        return $this->notes;
    }

    public function initHasNotes()
    {
        $this->notes = '';
    }

    public function dataHasNotes($data)
    {
        $data->notes = $this->notes;
    }
}
