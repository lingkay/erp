<?php

namespace Hris\ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\HasType;


use DateTime;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="tools_deposit")
 */
class EmployeeDeposit
{
    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;
    use HasType;
    

    const TYPE_RETURN = "Return";
    const TYPE_DEDUCTION = "Deduction";


    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $debit;

    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $credit;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;


    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Deposit")
     * @ORM\JoinColumn(name="deposit_id", referencedColumnName="id")
     */
    protected $deposit_type;



	/** @ORM\Column(type="datetime", nullable=true) */
    protected $date_deposit;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_returned;

    /** @ORM\Column(type="string", length=4, nullable=true) */
    protected $cutoff;


    public function __construct()
    {
        $this->initHasName();
        $this->flag_given = false;
    }

    public function setDateDeposit($date_deposit)
    {
    	$this->date_deposit = $date_deposit;
    	return $this;
    }

    public function getDateDeposit()
    {
    	return $this->date_deposit;
    }

    public function setDateReturned($date_returned)
    {
        $this->date_returned = $date_returned;
        return $this;
    }

    public function getDateReturned()
    {
        return $this->date_returned;
    }


    public function setCutoff($cutoff)
    {
    	$this->cutoff = $cutoff;
    	return $this;	
    }

    public function getCutoff()
    {
    	return $this->cutoff;
    }

    public function getYearDeposit()
    {
    	return $this->date_deposit->format('Y');
    }

    public function getMonthDeposit()
    {
    	return $this->date_deposit->format('m');
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return IssuedProperty
     */
    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \Hris\WorkforceBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function getEmployeeName()
    {
        return $this->employee->getDisplayName();
    }

    public function getTeam()
    {
        if($this->employee != null ){
            return $this->employee->getArea()->getName();
        }else {
            return "";
        }
    }


      /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Bonus
     */
    public function setDebit($debit)
    {
        $this->debit = $debit;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getDebit()
    {
        return $this->debit;
    }

    public function setCredit($credit)
    {
    	$this->credit = $credit;
    	return $this;
    }

    public function getCredit()
    {
    	return $this->credit;
    }

    public function getDepositType()
    {
        return $this->deposit_type;
    }

    public function setDepositType($deposit_type)
    {
        $this->deposit_type = $deposit_type;
        return $this;
    }

     public function getAmount()
    {
        switch($this->type){
        case self::TYPE_RETURN: 
            return $this->getDebit();
            break;
        case self::TYPE_DEDUCTION:
            return $this->getCredit();
            break;
        }
    }

  
}
