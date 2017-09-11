<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_overtime_rate")
 */
class PayOvertimeRate
{
    use HasGeneratedID;

    /** @ORM\Column(type="string")*/
    protected $description;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $rate;

    public function __construct()
    {
    }

    
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setRate($amt)
    {
        $this->rate = $amt;
        return $this;
    }

    public function getRate()
    {
        return $this->rate;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        return $data;
    }
}
