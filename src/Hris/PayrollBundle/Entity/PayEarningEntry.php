<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasNotes;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_earning_entry")
 */
class PayEarningEntry
{
    use HasGeneratedID;
    use HasNotes;

    const TYPE_GROSS = "Gross Pay";
    const TYPE_OVERTIME = "Overtime";
    const TYPE_HOLIDAY = "Holiday";
    const TYPE_REIMBURSEMENT = "Reimbursement";
    const TYPE_INCENTIVE = "Incentive";
    const TYPE_OTHERS = "Others";


    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="PayPayroll", inversedBy="earning_entries")
     * @ORM\JoinColumn(name="payroll_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $payroll;

    /** @ORM\Column(type="string", length=20) */
    protected $type;


    /** @ORM\Column(type="boolean") */
    protected $flag_taxable;


    public function __construct()
    {
        $this->flag_taxable = true;
    }

    public function setAmount($amt)
    {
        $this->amount = $amt;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
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

    public function setPayroll(\Hris\PayrollBundle\Entity\PayPayroll $payroll)
    {
        $this->payroll = $payroll;
        return $this;
    }

    public function getPayroll()
    {
        return $this->payroll;
    }

    public function isTaxable()
    {
        return $this->flag_taxable;
    }

    public function setTaxable($taxable)
    {
        $this->flag_taxable = $taxable;
        return $this;
    }
    
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $data->type = $this->type;
        $data->notes = $this->notes;
        $data->amount = $this->amount;
        $data->is_taxable = $this->flag_taxable;
    
        return $data;
    }
}
