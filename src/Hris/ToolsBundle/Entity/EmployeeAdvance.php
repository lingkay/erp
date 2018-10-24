<?php

namespace Hris\ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\HasType;

use Hris\ToolsBundle\Entity\EmployeeAdvanceEntry;

use DateTime;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="tools_advance")
 */
class EmployeeAdvance
{
    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;
    use HasType;
    

    const TYPE_STRAIGHT = "Straight";
    const TYPE_INSTALLMENT = "Installment";



    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $total;


    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $balance;

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

    /**
     * @ORM\ManyToOne(targetEntity="Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="given_id", referencedColumnName="id")
     */
    protected $given_by;


	  /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_release;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_request;

    /** @ORM\Column(type="boolean") */
    protected $flag_fulldeduction;

    /** @ORM\Column(type="integer") */
    protected $deduction_no;


    /**
     * @ORM\OneToMany(targetEntity="EmployeeAdvanceEntry", mappedBy="advance", cascade={"persist"})
     */
    protected $entries;


    public function __construct()
    {
        $this->initHasName();
        $this->initTrackCreate();
        $this->flag_fulldeduction = false;
        $this->deduction_no = 0;
        $this->entries = new ArrayCollection();
    }

    public function setIsFillDeduction($flag_fulldeduction)
    {
        $this->flag_fulldeduction = $flag_fulldeduction;
        return $this;
    }
    
    public function isFullDeduction()
    {
      return $this->flag_fulldeduction;
    }

    public function setDateRelease($date_release)
    {
    	$this->date_release = $date_release;
    	return $this;
    }

    public function getDateRelease()
    {
    	return $this->date_release;
    }

    public function setDateRequest($date_request)
    {
        $this->date_request = $date_request;
        return $this;
    }

    public function getDateRequest()
    {
        return $this->date_request;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return IssuedProperty
     */
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

    public function getTeam()
    {
       return $this->team;
    }

    public function setTeam($team)
    {
        $this->team = $team;
        return $this;
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

    public function setBalance($balance)
    {
      $this->balance = $balance;
      return $this;
    }

    public function getBalance()
    {
      return $this->balance;
    }

    public function getEntries()
    {
      return $this->entries;
    }

    public function addEntry(EmployeeAdvanceEntry $entry)
    {
        // set purchase order
        $entry->setAdvance($this);

        // add entry
        $this->entries->add($entry);

        return $this;
    }

    public function deleteEntry(EmployeeAdvanceEntry $entry)
    {
        $this->entries->removeElement($entry);
        return $this;
    }

    public function clearEntries()
    {
        $this->entries->clear();
    }

    public function countEntries()
    {
      return $this->entries->count();
    }

    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
