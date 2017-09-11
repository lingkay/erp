<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use Hris\WorkforceBundle\Entity\Employee;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_dependent")
 */
class Dependent
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;


    /** @ORM\Column(type="string",length=20) */
    protected $status;

    /** @ORM\Column(type="string",length=100) */
    protected $first_name;

    /** @ORM\Column(type="string",length=100) */
    protected $middle_name;

    /** @ORM\Column(type="string",length=100) */
    protected $last_name;

    /** @ORM\Column(type="string",length=100) */
    protected $relation;

    /** @ORM\Column(type="date") */
    protected $birth_date;

    /** @ORM\Column(type="boolean") */
    protected $flag_qualified;


    public function __construct($profile,$checklist)
    {
        $this->profile = $profile;
        $this->checklist = $checklist;
        $this->status = self::STATUS_PENDING;
    }

    public function setEmployee(Employee $employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setBirthdate($birth_date)
    {
        $this->birth_date = $birth_date;
        return $this;
    }

    public function getBirthdate()
    {
        return $this->birth_date;
    }

    public function getRelation()
    {
        return $this->relation;
    }

    public function setRelation($relation)
    {
        $this->relation = $relation;
        return $this;
    }

    public function setQualified()
    {
        $this->flag_qualified = true;
        return $this;
    }

    public function isQualified()
    {
        return $this->flag_qualified;
    }


    public function toData()
    {

        $data = new stdClass();

        return $data;
    }
}
