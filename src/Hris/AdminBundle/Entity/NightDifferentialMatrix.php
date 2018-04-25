<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hris_night_differential_matrix")
 */
class NightDifferentialMatrix
{
    use HasGeneratedID;

    /** @ORM\Column(type="string", nullable=true)*/
    protected $bracket;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $time_from;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $time_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $rate;

    /** @ORM\Column(type="string", nullable=true)*/
    protected $displayId;

    public function __construct()
    {
    }

    public function setDisplayId($displayId)
    {
        $this->displayId = $displayId;
        return $this;
    }

    public function getDisplayId()
    {
        return $this->displayId;
    }

    public function setBracket($bracket)
    {
        $this->bracket = $bracket;
        return $this;
    }

    public function setTimeFrom(DateTime $time_from)
    {
        $this->time_from = $time_from;
        return $this;
    }

    public function getTimeFrom()
    {
        if ($this->time_from == null)
        {
            return $this->time_from;
        }
        else
        {
            return $this->time_from->format('g:i A');
        }
    }

    public function setTimeTo(DateTime $time_to)
    {
        $this->time_to = $time_to;
        return $this;
    }

    public function getTimeTo()
    {
        if ($this->time_to == null)
        {
            return $this->time_to;
        }
        else
        {
            return $this->time_to->format('g:i A');
        }
    }

    public function getBracket()
    {
        return $this->bracket;
    }

    public function setRate($rate)
    {
        $this->rate = $rate;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function getRateFormatted()
    {
        return $this->rate. '%';
    }



    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        return $data;
    }
}
