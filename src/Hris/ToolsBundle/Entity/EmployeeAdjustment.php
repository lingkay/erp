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
 * @ORM\Table(name="tools_adjustment")
 */
class EmployeeAdjustment
{
    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;
    use HasType;
    

    const TYPE_ADD = "Add";
    const TYPE_DEDUCTION = "Deduction";

    const ADJUSTMENT_FINE = "Fine";
    const ADJUSTMENT_BASIC = "Basic Salary";
    const ADJUSTMENT_13TH = "13th Month";
    const ADJUSTMENT_OVERPAID = "Over Paid";


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
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\Areas")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
     */
    protected $team;


  	/** @ORM\Column(type="datetime", nullable=true) */
    protected $date_adjustment;


    /** @ORM\Column(type="string", length=4, nullable=true) */
    protected $cutoff;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $adjustment_type;


    public function __construct()
    {
        $this->initHasName();
        $this->initTrackCreate();
        $this->debit = 0.0;
        $this->credit = 0.0;
    }

    public function setDateAdjustment($date_adjustment)
    {
    	$this->date_adjustment = $date_adjustment;
    	return $this;
    }

    public function getDateAdjustment()
    {
    	return $this->date_adjustment;
    }


    public function setAdjustmentType($adjustment_type)
    {
      $this->adjustment_type = $adjustment_type;
      return $this;
    }

    public function getAdjustmentType()
    {
      return $this->adjustment_type;
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

    public function getYearAdjustment()
    {
    	return $this->date_adjustment->format('Y');
    }

    public function getMonthAdjustment()
    {
    	return $this->date_adjustment->format('m');
    }

    public function setEmployee( $employee = null)
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
       return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
        return $this;
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

    public function getAmount()
    {
        switch($this->type){
        case self::TYPE_ADD: 
            return $this->getDebit();
            break;
        case self::TYPE_DEDUCTION:
            return $this->getCredit();
            break;
        }
    }

    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
