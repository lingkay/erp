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
 * @ORM\Table(name="tools_advance_entry")
 */
class EmployeeAdvanceEntry
{
    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $deduction;

    /** @ORM\Column(type="decimal", precision=15, scale=2, nullable=true) */
    protected $balance;


    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_deduction;

    /** @ORM\Column(type="string", length=4, nullable=true) */
    protected $cutoff;

    /** @ORM\Column(type="integer") */
    protected $count;

    /**
     * @ORM\ManyToOne(targetEntity="EmployeeAdvance")
     * @ORM\JoinColumn(name="entries", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $advance;


    public function __construct()
    {
        $this->initHasName();
        $this->flag_given = false;
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


    public function setCutoff($cutoff)
    {
    	$this->cutoff = $cutoff;
    	return $this;	
    }

    public function getCutoff()
    {
    	return $this->cutoff;
    }

    public function getYearRelease()
    {
    	return $this->date_release->format('Y');
    }

    public function getMonthRelease()
    {
    	return $this->date_release->format('m');
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

    public function setAdvance($advance)
    {
        $this->advance = $advance;
        return $this;
    }

    public function getAdvance()
    {
        return $this->advance;
    }

    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
