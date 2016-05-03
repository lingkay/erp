<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_thirteenth")
 */
class PayThirteenth
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManytoOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=5) */
    protected $fs_year;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_taxable;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $tax;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_amount;

    /**
     * @ORM\OneToMany(targetEntity="PayEarningEntry", mappedBy="payroll", cascade={"persist"})
     */
    protected $monthly_entries;

    
    /** @ORM\Column(type="boolean") */
    protected $flag_locked;

    public function __construct()
    {
        $this->monthly_entries = new ArrayCollection();
        $this->total_taxable = 0.0;
        $this->total_amount = 0.0;
        $this->tax = 0;
        $this->flag_locked = false;
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

   
    

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }
}
