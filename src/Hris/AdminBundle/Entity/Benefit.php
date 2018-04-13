<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_benefit")
 */
class Benefit
{
    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;


    const TYPE_DAYS     =   "Quantified by Days";
    const TYPE_MONEY    =   "Quantified by Monetary Value";

    const GENDER_MALE = "Male";
    const GENDER_FEMALE = "Female";

    const NAME_VACATION = "Vacation Leave";
    const NAME_SICK = "Sick Leave";
    const NAME_MATERNITY = "Maternity Leave";
    const NAME_SSS = "SSS";
    const NAME_PHILHEALTH = "PhilHealth";
    const NAME_PAGIBIG = "PAG-IBIG";
    const NAME_PATERNITY = "Paterniy Leave";
    const NAME_PARENTAL = "Parental Leave";
    const NAME_CASHBOND = "Savings";

    /** @ORM\Column(type="boolean") */
    protected $paid_by;

    /** @ORM\Column(type="decimal", length=80, precision=5, scale=2, nullable=true) */
    protected $employee_share;

    /** @ORM\Column(type="decimal", length=80, precision=5, scale=2, nullable=true) */
    protected $employer_share;

    /** @ORM\Column(type="text", nullable=true) */
    protected $emp_status;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $gender;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $department;


    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);
        $data->emp_status = $this->emp_status;
        $data->type = $this->type;

        return $data;
    }

    public function setPaidBy($paid_by)
    {
        $this->paid_by = $paid_by;

        return $this;
    }

    public function getPaidBy()
    {
        return $this->paid_by;
    }

    public function setEmployeeShare($employee_share)
    {
        $this->employee_share = $employee_share;
        return $this;
    }

    public function getEmployeeShare()
    {
        return $this->employee_share;
    }

    public function getEmployeeShareFormatted()
    {
        return $this->employee_share . '%';
    }

    public function setEmployerShare($employer_share)
    {
        $this->employer_share = $employer_share;
        return $this;
    }

    public function getEmployerShare()
    {
        return $this->employer_share;
    }

    public function getEmployerShareFormatted()
    {
        return $this->employer_share . '%';
    }

    public function setEmpStatus($empStatus)
    {
        $this->emp_status = serialize($empStatus);

        return $this;
    }

    public function getEmpStatus()
    {
        return unserialize($this->emp_status);
    }

    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

     public function setGender($gender)
    {
        $this->gender = serialize($gender);

        return $this;
    }

    public function getGender()
    {
        return unserialize($this->gender);
    }

    public function getDepartment()
    {
        return $this->department;
    }

     public function setDepartment($department)
    {
        $this->department = $department;

        return $this;
    }

   
}
