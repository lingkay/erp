<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_sss_rate")
 */
class PaySSSRate
{
    use HasGeneratedID;

    /** @ORM\Column(type="string", length=80) */
    protected $salary_bracket;

    /** @ORM\Column(type="decimal",precision=15, scale=2) */
    protected $min_amount;

    /** @ORM\Column(type="decimal",precision=15, scale=2) */
    protected $max_amount;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $salary_credit;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $employee_contribution;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $employer_contribution;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $total_contribution;


    public function __construct()
    {
    }

    public function setBracket($bracket)
    {
        $this->salary_bracket = $bracket;
        return $this;
    }

    public function getBracket()
    {
        return $this->salary_bracket;
    }
    
    public function setMinimum($min)
    {
        $this->min_amount = $min;
        return $this;
    }

    public function getMinimum()
    {
        return $this->min_amount;
    }

    public function setMaximum($max)
    {
        $this->max_amount = $max;
        return $this;
    }

    public function getMaximum()
    {
        return $this->max_amount;
    }

    public function setSalaryCredit($msc)
    {
        $this->salary_credit = $msc;
        return $this;
    }

    public function getSalaryCredit()
    {
        return $this->salary_credit;
    }

    public function setEmployeeContribution($ee)
    {
        $this->employee_contribution = $ee;
        return $this;
    }

    public function getEmployeeContribution()
    {
        return $this->employee_contribution;
    }

    public function setEmployer($er)
    {
        $this->employer_contribution = $er;
        return $this;
    }

    public function getEmployer()
    {
        return $this->employer_contribution;
    }

    public function setTotal($contribution)
    {
        $this->total_contribution = $contribution;
        return $this;
    }

    public function getTotal()
    {
        return $this->total_contribution;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        $data->salary_bracket = $this->salary_bracket;
        $data->min_amount = $this->min_amount;
        $data->max_amount = $this->max_amount;
        $data->salary_credit = $this->salary_credit;
        $data->employee_contribution = $this->employee_contribution;
        $data->employer_contribution = $this->employer_contribution;
        $data->total_contribution = $this->total_contribution;
        return $data;
    }
}

