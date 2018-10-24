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
        $this->balance= 0;
        $this->count = 1;
        $this->date_deduction = new DateTime();
        $this->initTrackCreate();
    }

    public function setDateDeduction($date_deduction)
    {
    	$this->date_deduction = $date_deduction;
    	return $this;
    }

    public function getDateDeduction()
    {
    	return $this->date_deduction;
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


    public function setCount($count)
    {
        $this->count = $count;
        return $this;
    }

    public function getCount()
    {
        return $this->count;
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

    public function getYearDeduction()
    {
    	return $this->date_deduction->format('Y');
    }

    public function getMonthDeduction()
    {
    	return $this->date_deduction->format('m');
    }

    public function setDeduction($deduction)
    {
        $this->deduction = $deduction;
        return $this;
    }

    public function getDeduction()
    {
        return $this->deduction;
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

    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
