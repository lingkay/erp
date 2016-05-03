<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_tax")
 */
class PayTax
{
    use HasGeneratedID;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\PayrollBundle\Entity\PayPayroll")
     * @ORM\JoinColumn(name="payroll_id",referencedColumnName="id")
     */
    protected $payroll_id;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\PayrollBundle\Entity\PayTaxMatrix")
     * @ORM\JoinColumn(name="taxmatrix_id", referencedColumnName="id")
     */
    protected $taxmatrix_id;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $taxable_amount;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $excess_amount;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $taxed_amount;


    public function __construct()
    {
    }

    public function setPayroll(\Hris\PayrollBundle\Entity\PayPayroll $payroll)
    {
        $this->payroll_id = $payroll;
        return $this;
    }

    public function getPayroll()
    {
        return $this->payroll_id;
    }

    public function setTaxMatrix(\Hris\PayrollBundle\Entity\PayTaxMatrix $matrix)
    {
        $this->taxmatrix_id = $matrix;
        return $this;
    }

    public function getTaxMatrix()
    {
        return $this->taxmatrix_id;
    }

    public function setTaxable($amt)
    {
        $this->taxable_amount = $amt;
        return $this;
    }

    public function getTaxable()
    {
        return $this->taxable_amount;
    }

    public function setExcess($amt)
    {
        $this->excess_amount = $amt;
        return $this;
    }

    public function getExcess()
    {
        return $this->excess_amount;
    }

    public function setTaxedAmount($amt)
    {
        $this->taxed_amount = $amt;
        return $this;
    }

    public function getTaxedAmount()
    {
        return $this->taxed_amount;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        return $data;
    }
}
