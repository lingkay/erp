<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasCode;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_taxstatus")
 */
class PayTaxStatus
{
    use HasGeneratedID;
    use HasCode;

    const PERSONAL_EXEMPTION = 50000;
    const ADDITIONAL_EXEMPTION = 25000;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $personal_exemption;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $additional_exemption;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $total_exemption;

    public function __construct()
    {
    }

    public function setPersonal($personal)
    {
        $this->personal_exemption = $personal;
        return $this;
    }

    public function getPersonal()
    {
        return $this->personal_exemption;
    }

    public function setAdditional($additional)
    {
        $this->additional_exemption = $additional;
        return $this;
    }

    public function getAdditional()
    {
        return $this->additional_exemption;
    }

    public function setTotal($total)
    {
        $this->total_exemption = $total;
        return $this;
    }

    public function getTotalExemption()
    {
        return $this->total_exemption;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasCode($data);

        return $data;
    }
}
