<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\MediaBundle\Template\Entity\HasUpload;
use Gist\ContactBundle\Template\Entity\HasAddress;
use Gist\ContactBundle\Template\Entity\HasPhones;
use Hris\WorkforceBundle\Entity\EmployeeChecklist;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_employee_profile")
 */
class Profile
{
    use HasGeneratedID;
    use HasUpload;
    use HasAddress;
    use HasPhones;


    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee", inversedBy="profile", cascade={"persist"})
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="date", nullable=true) */
    protected $birthday;

    /** @ORM\Column(type="string", length=30 , nullable=true) */
    protected $tin;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $sss;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $philhealth;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $pagibig;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $bank_account;

    /** @ORM\OneToMany(targetEntity="\Hris\WorkforceBundle\Entity\EmployeeChecklist", mappedBy="profile", cascade={"persist"}) */
    protected $employee_checklist;

    public function __construct()
    {
        $this->employee_checklist = new ArrayCollection();
    }

    public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    public function getBirthday()
    {
        return $this->birthday;
    }

    public function setTin($tin)
    {
        $this->tin = $tin;
        return $this;
    }

    public function getTin()
    {
        return $this->tin;
    }

    public function setSss($sss)
    {
        $this->sss = $sss;
        return $this;
    }

    public function getSss()
    {
        return $this->sss;
    }

    public function setPhilhealth($philhealth)
    {
        $this->philhealth = $philhealth;
        return $this;
    }

    public function getPhilhealth()
    {
        return $this->philhealth;
    }

    public function setPagibig($pagibig)
    {
        $this->pagibig = $pagibig;
        return $this;
    }

    public function getPagibig()
    {
        return $this->pagibig;
    }

    public function setBankAccount($account)
    {
        $this->bank_account = $account;
        return $this;
    }

    public function getBankAccount()
    {
        return $this->bank_account;
    }

    public function getChecklist()
    {
        return $this->employee_checklist;
    }

    public function addChecklist(EmployeeChecklist $checklist)
    {
        // add entry
        $this->employee_checklist->add($checklist);
        return $this;
    }

    public function clearChecklist()
    {
        $this->employee_checklist->clear();
        return $this;
    }


    public function toData()
    {

        $data = new stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasAddress($data);

        $data->birthday = $this->birthday->format('m/d/y');
        $data->sss = $this->sss;
        $data->philhealth = $this->philhealth;
        $data->pagibig = $this->pagibig;
        $data->tin = $this->tin;

        return $data;
    }
}
