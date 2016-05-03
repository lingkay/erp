<?php

namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use Hris\RecruitmentBundle\Entity\Application;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_experience")
 */
class ApplicationExperience
{

    use HasGeneratedID;
    use TrackCreate;

    /** 
    * @ORM\ManyToOne(targetEntity="Application", inversedBy="experience")
    * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
    */
    protected $app_id;

    /** @ORM\Column(type="string") */
    protected $name_address_company;

    /** @ORM\Column(type="string") */
    protected $position;

    /** @ORM\Column(type="integer") */
    protected $duration;

    /** @ORM\Column(type="integer") */
    protected $salary_start;

    /** @ORM\Column(type="integer") */
    protected $salary_last;

    /** @ORM\Column(type="string") */
    protected $reason;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setApplication(Application $application)
    {
    	$this->app_id = $application;
    	return $this;
    }

    public function getApplication()
    {
    	return $this->app_id;
    }

    public function setCompanyNameAddress($detail)
    {
    	$this->name_address_company = $detail;
    	return $this;
    }

    public function getCompanyNameAddress()
    {
    	return $this->name_address_company;
    }

    public function setPositionHeld($position)
    {
    	$this->position = $position;
    	return $this;
    }

    public function getPositionHeld()
    {
    	return $this->position;
    }

    public function setEmploymentDuration($emp_duration)
    {
    	$this->duration = $emp_duration;
    	return $this;
    }

    public function getEmploymentDuration()
    {
    	return $this->duration;
    }

    public function setStartingSalary($salary)
    {
    	$this->salary_start = $salary;
    	return $this;
    }

    public function getStartingSalary()
    {
    	return $this->salary_start;
    }

    public function setLastSalary($salary)
    {
    	$this->salary_last = $salary;
    	return $this;
    }

    public function getLastSalary()
    {
    	return $this->salary_last;
    }

    public function setReason($reason)
    {
    	$this->reason = $reason;
    	return $this;
    }

    public function getReason()
    {
    	return $this->reason;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

}

?>