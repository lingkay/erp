<?php

namespace Hris\BiometricsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use stdClass;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="biometrics_attendance")
 */
class BiometricsAttendance
{

    use HasGeneratedID;

    /** @ORM\Column(type="date") */
    protected $date;
    
    /** @ORM\Column(type="datetime", nullable=true) */
    protected $checktime;

    /** @ORM\Column(type="text") */
    protected $checktype;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;


    public function __construct()
    {

    }

    public function setCheckTime($checktime)
    {
        $this->checktime = $checktime;
        return $this;
    }

    public function setCheckType($checktype)
    {
        $this->checktype = $checktype;
        return $this;
    }

    public function getCheckTime()
    {
        return $this->checktime->format('m/d/Y g:i A');;
    }

    public function getCheckType()
    {
        return $this->checktype;
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $data->checktime = $this->checktime;
        $data->checktype = $this->checktype;
        return $data;
    }

    
}
