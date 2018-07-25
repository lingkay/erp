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
 */
class EmployeeBonusFine
{

    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;
    use HasType;
    

    const TYPE_SALARY = "Salary";
    const TYPE_CASH = "Cash";

    const BFTYPE_BONUS = "Bonus";
    const BFTYPE_FINE = "Fine";

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Bonus")
     * @ORM\JoinColumn(name="bonus_id", referencedColumnName="id")
     */
    protected $bonus;


    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Fines")
     * @ORM\JoinColumn(name="fine_id", referencedColumnName="id")
     */
    protected $fine;


    /** @ORM\Column(type="string", length=40, nullable=true) */
    protected $bf_type;

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
     * @ORM\ManyToOne(targetEntity="Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="given_id", referencedColumnName="id")
     */
    protected $given_by;

	/** @ORM\Column(type="datetime", nullable=true) */
    protected $date_released;

    /** @ORM\Column(type="string", length=4, nullable=true) */
    protected $cutoff;

	/** @ORM\Column(type="boolean", ) */
    protected $flag_given;

    public function __construct()
    {
        $this->initHasName();
        $this->flag_given = false;
        $this->date_released = new DateTime();
    }

    public function setDateReleased($date_released)
    {
    	$this->date_released = $date_released;
    	return $this;
    }

    public function getDateReleased()
    {
    	return $this->date_released;
    }


    public function setGivenBy($given_by)
    {
    	$this->given_by = $given_by;
    	return $this;
    }

    public function getGivenBy()
    {
    	return $this->given_by;
    }

    public function getGivenName()
    {
    	return $this->given_by->getDisplayName();
    }

    public function isGiven()
    {
    	return $this->flag_given;
    }

    public function setIsGiven($flag_given)
    {
    	$this->flag_given = $flag_given;
    	return $this;
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

    public function getYearGiven()
    {
    	return $this->date_released->format('Y');
    }

    public function getMonthGiven()
    {
    	return $this->date_released->format('m');
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

    public function setBFType($type)
    {
        $this->bf_type = $type;
        return $this;
    }

    public function getBFType()
    {
        return $this->bf_type;
    }

    public function getReasonName()
    {
    	switch($this->bf_type){
    	case self::BFTYPE_BONUS: 
    		return $this->bonus->getName();
    		break;
    	case self::BFTYPE_FINE:
    		return $this->fine->getName();
    		break;
    	}
    }

    public function getReason()
    {
    	switch($this->bf_type){
    	case self::BFTYPE_BONUS: 
    		return $this->bonus;
    		break;
    	case self::BFTYPE_FINE:
    		return $this->fine;
    		break;
    	}
    }

    public function setReason($bonus_fine)
    {
    	switch($this->bf_type){
    	case self::BFTYPE_BONUS: 
    		return $this->bonus = $bonus_fine;
    		break;
    	case self::BFTYPE_FINE:
    		return $this->fine = $bonus_fine;
    		break;
    	}
    }



    public function getAmount()
    {
    	switch($this->bf_type){
    	case self::BFTYPE_BONUS: 
    		return $this->getDebit();
    		break;
    	case self::BFTYPE_FINE:
    		return $this->getCredit();
    		break;
    	}
    }
}