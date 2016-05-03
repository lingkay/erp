<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_govbenefits_paid")
 */
class PayGovBenefitsPaid
{
    const TYPE_SSS = "SSS";
    const TYPE_PHILHEALTH = "Philhealth";
    const TYPE_PAGIBIG = "Pagibig";

    use HasGeneratedID;
    
    /**
     * @ORM\ManytoOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=40) */
    protected $type;

    /** @ORM\Column(type="string", length=5) */
    protected $fs_month;

    /** @ORM\Column(type="string", length=5) */
    protected $fs_year;

    public function __construct()
    {

    }

    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $ee)
    {
        $this->employee = $ee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setFiscalMonth($month)
    {
        $this->fs_month = $month;
        return $this;
    }

    public function getFiscalMonth()
    {
        return $this->fs_month;
    }

    public function setFiscalYear( $year)
    {
        $this->fs_year = $year;
        return $this;
    }

    public function getFiscalYear()
    {
        return $this->fs_year;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        return $data;
    }
}
