<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_13th")
 */
class Pay13th
{
    use HasGeneratedID;

    /**
     * @ORM\ManytoOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_taxable;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $tax;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_amount;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $sub_total;

    /** @ORM\Column(type="string", length=5) */
    protected $year;

    /**
     * @ORM\OneToMany(targetEntity="Pay13thEntry", mappedBy="pay_13th", cascade={"persist"})
     */
    protected $entries;

    /** @ORM\Column(type="boolean") */
    protected $flag_locked;


    public function __construct()
    {
        $this->entries = new ArrayCollection();
        $this->total_taxable = 0.0;
        $this->total_amount = 0.0;
        $this->sub_total = 0.0;
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
        $sum = 0;
        foreach ($this->entries as $entry) {
            $sum += $entry->getTotal();
        }
        $this->total_taxable = $sum/12;
        return $this->total_taxable;
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

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function getYear()
    {
        return $this->year;
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

    public function setSubTotal($amt)
    {
        $this->sub_total = $amt;
        return $this;
    }

    public function getSubTotal()
    {
        return $this->sub_total;
    }


    public function getEntries()
    {
        return $this->entries;
    }

    public function addEarningEntry(Pay13thEntry $entry)
    {
        // set purchase order
        $entry->setPay13th($this);

        // add entry
        $this->entries->add($entry);
        $this->sub_total += $entry->getTotal();

        return $this;
    }

    public function clearEarningEntries()
    {
        $this->entries->clear();
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

    public function recompute()
    {
        $sum = 0;
        foreach ($this->entries as $entry) {
            $sum += $entry->getTotal();
        }

        $this->sub_total = $sum;
        $this->total_taxable = $sum/12;

    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }
}
