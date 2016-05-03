<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Hris\PayrollBundle\Entity\Pay13th;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_13thentry")
 */
class Pay13thEntry
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

    /**
     * @ORM\ManytoOne(targetEntity="Pay13th", inversedBy="entries")
     * @ORM\JoinColumn(name="pay13th_id", referencedColumnName="id")
     */
    protected $pay_13th;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_earning;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_deduction;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total;

    public function __construct()
    {
        $this->total_earning = 0.0;
        $this->total_deduction = 0.0;
        $this->total = 0.0;
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

  
    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $ee)
    {
        $this->employee = $ee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setPay13th(Pay13th $pay13th)
    {
        $this->pay_13th = $pay13th;
        return $this;
    }

    public function getPay13th()
    {
        return $this->pay_13th;
    }

    public function setEarning($earning)
    {
        $this->total_earning = $earning;
        return $this;
    }

    public function getEarning()
    {
        return $this->total_earning;
    }

    public function setDeduction($deduction)
    {
        $this->total_deduction = $deduction;
        return $this;
    }

    public function getDeduction()
    {
        return $this->total_deduction;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function getTotal()
    {
        return $this->total;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }
}
