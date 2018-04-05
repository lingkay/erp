<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;

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

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $hrs_from;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $hrs_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $rate;

    public function __construct()
    {
    }

    public function setBracket($bracket)
    {
        $this->bracket = $bracket;
        return $this;
    }

    public function getBracket()
    {
        return $this->bracket;
    }

    public function setMinimum($amt_from)
    {
        $this->hrs_from = $amt_from;
        return $this;
    }

    public function getMinimum()
    {
        return $this->hrs_from;
    }

    public function setMaximum($amt_to)
    {
        $this->hrs_to = $amt_to;
        return $this;
    }

    public function getMaximum()
    {
        return $this->hrs_to;
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
