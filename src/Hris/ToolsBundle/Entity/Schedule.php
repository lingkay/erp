<?php

namespace Hris\ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="tools_schedule")
 */
class Schedule
{
    use HasGeneratedID;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\Areas")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;

    /** @ORM\Column(type="date") */
    protected $date;

    public function __construct()
    {
//        $this->initHasName();
    }

    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }
}
