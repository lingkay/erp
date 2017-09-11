<?php

namespace Gist\CoreBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;

trait HasGeneratedID
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function getID()
    {
        return $this->id;
    }

    protected function initHasGeneratedID()
    {
    }

    public function dataHasGeneratedID($data)
    {
        $data->id = $this->id;
    }
}
