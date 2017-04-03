<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_payroll")
 */
class PayPayroll
{
    use HasGeneratedID;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\PayrollBundle\Entity\PayPayrollPeriod")
     * @ORM\JoinColumn(name="payroll_period_id", referencedColumnName="id")
     */
    protected $payroll_period;

    /**
     * @ORM\ManytoOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_earning;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_deduction;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_taxable;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_nontaxable;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $tax;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_amount;

    /**
     * @ORM\OneToMany(targetEntity="PayEarningEntry", mappedBy="payroll", cascade={"persist"})
     */
    protected $earning_entries;

    /**
     * @ORM\OneToMany(targetEntity="PayDeductionEntry", mappedBy="payroll", cascade={"persist"})
     */
    protected $deduction_entries;
    
    /** @ORM\Column(type="boolean") */
    protected $flag_locked;

    public function __construct()
    {
        $this->earning_entries = new ArrayCollection();
        $this->deduction_entries = new ArrayCollection();
        $this->total_earning = 0.0;
        $this->total_deduction = 0.0;
        $this->total_taxable = 0.0;
        $this->total_amount = 0.0;
        $this->total_nontaxable = 0.0;
        $this->tax = 0;
        $this->flag_locked = false;
    }

    public function setPayrollPeriod(\Hris\PayrollBundle\Entity\PayPayrollPeriod $period)
    {
        $this->payroll_period = $period;
        return $this;
    }

    public function getPayrollPeriod()
    {
        return $this->payroll_period;
    }

    public function setTotalTaxable($tax)
    {
        $this->total_taxable = $tax;
        return $this;
    }

    public function getTotalTaxable()
    {
        return $this->total_taxable;
    }

    public function setTotalNontaxable($tax)
    {
        $this->total_nontaxable = $tax;
        return $this;
    }

    public function getTotalNontaxable()
    {
        return $this->total_nontaxable;
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

    public function setTax($tax)
    {
        $this->tax = $tax;
        return $this;
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function setTotal($amt)
    {
        $this->total_amount = $amt;
        return $this;
    }

    public function getTotal()
    {
        return $this->total_amount;
    }

    public function isLocked()
    {
        return $this->flag_locked;
    }

    public function lock()
    {
        $this->flag_locked = true;
        return $this;
    }

    public function unlock()
    {
        $this->flag_locked = false;
        return $this;
    }

    public function getEarningEntries()
    {
        return $this->earning_entries;
    }

    public function getEarningEntry($entry_name){
        foreach ($this->earning_entries as $entry) {
            if($entry_name === $entry->getType()){
                return $entry;
            }
        }
        return null;
    }

    public function addEarningEntry(PayEarningEntry $entry)
    {
        // set purchase order
        $entry->setPayroll($this);

        // add entry
        $this->earning_entries->add($entry);

        return $this;
    }

    public function deleteEarningEntry(PayEarningEntry $entry)
    {
        $this->earning_entries->removeElement($entry);
        return $this;
    }

    public function clearEarningEntries()
    {
        $this->earning_entries->clear();
    }

    public function getDeductionEntries()
    {
        return $this->deduction_entries;
    }

    public function getDeductionEntry($entry_name){
        foreach ($this->deduction_entries as $entry) {
            if($entry_name === $entry->getType()){
                return $entry;
            }
        }
        return null;
    }

    public function addDeductionEntry(PayDeductionEntry $entry)
    {
        // set purchase order
        $entry->setPayroll($this);

        // add entry
        $this->deduction_entries->add($entry);

        return $this;
    }

    public function deleteDeductionEntry(PayDeductionEntry $entry)
    {
        $this->deduction_entries->removeElement($entry);
        return $this;
    }

    public function clearDeductionEntries()
    {
        $this->deduction_entries->clear();
    }
    

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }
}
