<?php

namespace Hris\AdminBundle\Entity\Leave;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;
use Catalyst\CoreBundle\Template\Entity\HasNotes;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Hris\AdminBundle\Entity\Leave\Employee;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="leave_rules")
 */
class LeaveRules
{
    use HasGeneratedID;
    use TrackCreate;
    use HasName;
    use HasNotes;

    const GENDER_MALE = "Male";
    const GENDER_FEMALE = "Female";

    const EMP_PROBATIONARY = "Probationary";
    const EMP_CONTRACTUAL = "Contractual";
    const EMP_REGULAR = "Regular";

    const ACCRUE_WEEK = 'Weekly' ;
    const ACCRUE_MONTH = 'Monthly' ;
    const ACCRUE_QUARTER = 'Quarterly' ;
    const ACCRUE_YEAR = 'Yearly';

    const COUNT_YEAR = 'per Year';
    const COUNT_REQUEST = 'per Request';

    const PAY_NONE = 'None';
    const PAY_FULL = 'Full';
    const PAY_HALF = 'Half';

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Leave\LeaveType")
     * @ORM\JoinColumn(name="leave_type_id", referencedColumnName="id")
     */
    protected $leave_type;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\JobLevel")
     * @ORM\JoinColumn(name="job_level_id", referencedColumnName="id")
     */
    protected $job_level;    

    /** @ORM\Column(type="text", nullable=true) */
    protected $gender;

    /** @ORM\Column(type="text", nullable=true) */
    protected $emp_status;

    /** @ORM\Column(type="boolean") */
    protected $accrue_enabled;

    /** @ORM\Column(type="float", nullable=true) */ 
    protected $accrue_count;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $accrue_rule;

    /** @ORM\Column(type="boolean") */
    protected $carried_enabled;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $carry_percentage;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $carry_period;

    /** @ORM\Column(type="float") */ 
    protected $leave_count;

    /** @ORM\Column(type="string", length=20) */ 
    protected $count_type;
    
    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $service_months;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $payment_type;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $override;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $effectivity;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        $data->leave_type_id = $this->leave_type->getID();
        $data->employee_id = $this->employee->getID();
        $data->department_id = $this->department->getID();
        $data->job_level_id = $this->job_level->getID();
        $data->leave_type_name = $this->leave_type->getName();
        $data->employee_name = $this->employee->getDisplayName();
        $data->department_name = $this->department->getName();
        $data->job_level_name = $this->job_level->getName();
        $data->gender = $this->gender;
        $data->emp_status = $this->emp_status;
        $data->accrue_enabled = $this->accrue_enabled;
        $data->accrue_count = $this->accrue_count;
        $data->carried_enabled = $this->carried_enabled;
        $data->leave_count = $this->leave_count;
        $data->service_months = $this->service_months;
        $data->payment_type = $this->payment_type;
        $data->override = $this->override;
        $data->effectivity = $this->effectivity;

        return $data;
    }

    /**
     * Set gender
     *
     * @param string $gender
     *
     * @return LeaveRules
     */
    public function setGender($gender)
    {
        $this->gender = serialize($gender);

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return unserialize($this->gender);
    }

    public function getGenderList()
    {
        $genders = array();
        $gender = $this->getGender();
        foreach ($gender as $g => $name)
            $genders[$g] = $name;
        return implode(', ', $genders);
    }

    /**
     * Set empStatus
     *
     * @param string $empStatus
     *
     * @return LeaveRules
     */
    public function setEmpStatus($empStatus)
    {
        $this->emp_status = serialize($empStatus);

        return $this;
    }

    /**
     * Get empStatus
     *
     * @return string
     */
    public function getEmpStatus()
    {
        return unserialize($this->emp_status);
    }

    public function getEmpStatusList()
    {
        $status = array();
        $emp_status = $this->getEmpStatus();
        foreach ($emp_status as $e => $name)
            $status[$e] = $name;
        return implode(', ', $status);
    }

    /**
     * Set accrueEnabled
     *
     * @param boolean $accrueEnabled
     *
     * @return LeaveRules
     */
    public function setAccrueEnabled($accrueEnabled)
    {
        $this->accrue_enabled = $accrueEnabled;

        return $this;
    }

    /**
     * Get accrueEnabled
     *
     * @return boolean
     */
    public function getAccrueEnabled()
    {
        return $this->accrue_enabled;
    }

    /**
     * Set accrueCount
     *
     * @param float $accrueCount
     *
     * @return LeaveRules
     */
    public function setAccrueCount($accrueCount)
    {
        $this->accrue_count = $accrueCount;

        return $this;
    }

    /**
     * Get accrueCount
     *
     * @return float
     */
    public function getAccrueCount()
    {
        return $this->accrue_count;
    }

    /**
     * Set carriedEnabled
     *
     * @param boolean $carriedEnabled
     *
     * @return LeaveRules
     */
    public function setCarriedEnabled($carriedEnabled)
    {
        $this->carried_enabled = $carriedEnabled;

        return $this;
    }

    /**
     * Get carriedEnabled
     *
     * @return boolean
     */
    public function getCarriedEnabled()
    {
        return $this->carried_enabled;
    }

    /**
     * Set leaveCount
     *
     * @param float $leaveCount
     *
     * @return LeaveRules
     */
    public function setLeaveCount($leaveCount)
    {
        $this->leave_count = $leaveCount;

        return $this;
    }

    /**
     * Get leaveCount
     *
     * @return float
     */
    public function getLeaveCount()
    {
        return $this->leave_count;
    }

    /**
     * Set serviceMonths
     *
     * @param integer $serviceMonths
     *
     * @return LeaveRules
     */
    public function setServiceMonths($serviceMonths)
    {
        $this->service_months = $serviceMonths;

        return $this;
    }

    /**
     * Get serviceMonths
     *
     * @return integer
     */
    public function getServiceMonths()
    {
        return $this->service_months;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     *
     * @return LeaveRules
     */
    public function setPaymentType($paymentType)
    {
        $this->payment_type = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * Set leaveType
     *
     * @param \Hris\AdminBundle\Entity\Leave\LeaveType $leaveType
     *
     * @return LeaveRules
     */
    public function setLeaveType(\Hris\AdminBundle\Entity\Leave\LeaveType $leaveType = null)
    {
        $this->leave_type = $leaveType;

        return $this;
    }

    /**
     * Get leaveType
     *
     * @return \Hris\AdminBundle\Entity\Leave\LeaveType
     */
    public function getLeaveType()
    {
        return $this->leave_type;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return LeaveRules
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

    /**
     * Set department
     *
     * @param \Hris\AdminBundle\Entity\Department $department
     *
     * @return LeaveRules
     */
    public function setDepartment(\Hris\AdminBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Hris\AdminBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set jobLevel
     *
     * @param \Hris\AdminBundle\Entity\JobLevel $jobLevel
     *
     * @return LeaveRules
     */
    public function setJobLevel(\Hris\AdminBundle\Entity\JobLevel $jobLevel = null)
    {
        $this->job_level = $jobLevel;

        return $this;
    }

    /**
     * Get jobLevel
     *
     * @return \Hris\AdminBundle\Entity\JobLevel
     */
    public function getJobLevel()
    {
        return $this->job_level;
    }

    /**
     * Set override
     *
     * @param string $override
     *
     * @return LeaveRules
     */
    public function setOverride($override)
    {
        $this->override = $override;

        return $this;
    }

    /**
     * Get override
     *
     * @return string
     */
    public function getOverride()
    {
        return $this->override;
    }

    /**
     * Set effectivity
     *
     * @param string $effectivity
     *
     * @return LeaveRules
     */
    public function setEffectivity($effectivity)
    {
        $this->effectivity = $effectivity;

        return $this;
    }

    /**
     * Get effectivity
     *
     * @return string
     */
    public function getEffectivity()
    {
        return $this->effectivity;
    }

    /**
     * Set accrueRule
     *
     * @param string $accrueRule
     *
     * @return LeaveRules
     */
    public function setAccrueRule($accrueRule)
    {
        $this->accrue_rule = $accrueRule;

        return $this;
    }

    /**
     * Get accrueRule
     *
     * @return string
     */
    public function getAccrueRule()
    {
        return $this->accrue_rule;
    }

    /**
     * Set carryPercentage
     *
     * @param integer $carryPercentage
     *
     * @return LeaveRules
     */
    public function setCarryPercentage($carryPercentage)
    {
        $this->carry_percentage = $carryPercentage;

        return $this;
    }

    /**
     * Get carryPercentage
     *
     * @return integer
     */
    public function getCarryPercentage()
    {
        return $this->carry_percentage;
    }

    /**
     * Set carryPeriod
     *
     * @param integer $carryPeriod
     *
     * @return LeaveRules
     */
    public function setCarryPeriod($carryPeriod)
    {
        $this->carry_period = $carryPeriod;

        return $this;
    }

    /**
     * Get carryPeriod
     *
     * @return integer
     */
    public function getCarryPeriod()
    {
        return $this->carry_period;
    }

    /**
     * Set countType
     *
     * @param string $countType
     *
     * @return LeaveRules
     */
    public function setCountType($countType)
    {
        $this->count_type = $countType;

        return $this;
    }

    /**
     * Get countType
     *
     * @return string
     */
    public function getCountType()
    {
        return $this->count_type;
    }
}
