<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_employee")
 */
class Employee
{
    const STATUS_ENABLED = 'Enabled';
    const STATUS_DISABLED = 'Disabled';
    const STATUS_ARCHIVED = 'Archived';

    const EMP_PROBATIONARY = "Probationary";
    const EMP_CONTRACTUAL = "Contractual";
    const EMP_REGULAR = "Regular";
    const EMP_RESIGNED = "Resigned";

    const CIVIL_SINGLE = "Single";
    const CIVIL_MARRIED = "Married";
    const CIVIL_WIDOWED = "Widowed";
    const CIVIL_SEPARATED = "Legally Separated";
    const CIVIL_DIVORCED = "Divorced";

    use HasGeneratedID;
    use TrackCreate;


    /** @ORM\Column(type="string", length=80) */
    protected $first_name;

     /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $middle_name;

     /** @ORM\Column(type="string", length=80) */
    protected $last_name;

    /** @ORM\Column(type="string", length=10,nullable=true) */
    protected $gender;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\JobTitle")
     * @ORM\JoinColumn(name="job_title_id", referencedColumnName="id")
     */
    protected $job_title;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\JobLevel")
     * @ORM\JoinColumn(name="job_level_id", referencedColumnName="id")
     */
    protected $job_level;

    /** @ORM\Column(type="string", length=20) */
    protected $employment_status;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

    /**
     * @ORM\OneToMany(targetEntity="\Hris\WorkforceBundle\Entity\Attendance", mappedBy="employee", cascade={"persist"})
     */
    protected $attendance;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Schedules")
     * @ORM\JoinColumn(name="schedules_id", referencedColumnName="id")
     */
    protected $schedule;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\PayrollBundle\Entity\PayPeriod")
     * @ORM\JoinColumn(name="pay_period_id", referencedColumnName="id")
     */
    protected $pay_period;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $pay_rate;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\PayrollBundle\Entity\PayPeriod")
     * @ORM\JoinColumn(name="pay_schedule_id", referencedColumnName="id")
     */
    protected $pay_sched;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Profile", mappedBy="employee", cascade={"persist"})
     */
    protected $profile;

    /** 
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\Application")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $application_profile;

    /**
     * @ORM\OneToOne(targetEntity="\Gist\UserBundle\Entity\User", mappedBy="employee", cascade={"persist"})
     */
    protected $user;

    /** @ORM\Column(type="boolean") */
    protected $enabled;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $email;

    /**
     * @ORM\ManyToOne(targetEntity="Employee", inversedBy="subordinates")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $supervisor;

    /** @ORM\OneToMany(targetEntity="Employee", mappedBy="supervisor") */
    protected $subordinates;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $employee_code;

    /** @ORM\Column(type="date") */
    protected $date_hired;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $marital_status;

    /** @ORM\Column(type="boolean")*/
    protected $flag_new;

    /** @ORM\Column(type="json_array", nullable=true)*/
    protected $dependents;

    /** @ORM\Column(type="boolean")*/
    protected $exemption;

    /** @ORM\OneToMany(targetEntity="SalaryHistory", mappedBy="employee")
     *  @ORM\OrderBy({"date_create" = "DESC"})
     */
    protected $salary_history;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $cashbond_rate;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->enabled = true;
        $this->supervisor = null;
        $this->subordinates = new ArrayCollection();
        $this->flag_new = true;
        $this->exemption = false;
        $this->cashbond_rate = 0.0;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setApplication($application_profile)
    {
        $this->application_profile = $application_profile;
        return $this;
    }

    public function getApplication()
    {
        return $this->application_profile;
    }

    public function setFirstName($name)
    {
        $this->first_name = $name;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setMiddleName($name)
    {
        $this->middle_name = $name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function setLastName($name)
    {
        $this->last_name = $name;
        return $this;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setSchedule($schedule)
    {
        $this->schedule = $schedule;
        return $this;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function setEmploymentStatus($employment_status)
    {
        $this->employment_status = $employment_status;
        return $this;
    }

    public function getEmploymentStatus()
    {
        return $this->employment_status;
    }

    public function setJobTitle($job_title)
    {
        $this->job_title = $job_title;
        return $this;
    }

    public function getJobTitle()
    {
        return $this->job_title;
    }

    public function setJobLevel($job_level)
    {
        $this->job_level = $job_level;
        return $this;
    }

    public function getJobLevel()
    {
        return $this->job_level;
    }

    public function setLocation($location)
    {
        $this->location = $location;
        return $this;
    }

    public function getLocation()
    {
        return $this->location;
    }


    public function setEnabled($status)
    {
        $this->enabled = $status;
        return $this;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setProfile($profile)
    {
        $this->profile = $profile;
        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setEmployeeId($employee_code)
    {
        $this->employee_code = $employee_code;
        return $this;
    }

    public function getEmployeeId()
    {
        return $this->employee_code;
    }

    public function setSupervisor($supervisor)
    {
        $this->supervisor = $supervisor;
        return $this;
    }

    public function getSupervisor()
    {
        return $this->supervisor;
    }

    public function getSubordinates()
    {
        return $this->subordinates;
    }

    public function getDisplayName()
    {
        return $this->last_name.', '.$this->first_name;
    }

    public function setPayPeriod($pay_period)
    {
        $this->pay_period = $pay_period;
        return $this;
    }

    public function getPayPeriod()
    {
        return $this->pay_period;
    }

    public function setPaySchedule($pay_sched)
    {
        $this->pay_sched = $pay_sched;
        return $this;
    }

    public function getPaySchedule()
    {
        return $this->pay_sched;
    }

    public function setPayRate($pay_rate)
    {
        $this->pay_rate = $pay_rate;
        return $this;
    }

    public function getPayRate()
    {
        return $this->pay_rate;
    }

    public function setDateHired($date_hired)
    {
        $this->date_hired = $date_hired;
        return $this;
    }

    public function getDateHired()
    {
        return $this->date_hired;
    }

    public function getDateHiredFormatted()
    {
        return $this->date_hired->format('m/d/Y');
    }

    public function setAttendance($attendace)
    {
        $this->attendance = $attendance;
        return $this;
    }

    public function getAttendance()
    {
        return $this->attendance;
    }

    public function setMaritalStatus($marital_status)
    {
        $this->marital_status = $marital_status;
        return $this;
    }

    public function getMaritalStatus()
    {
        return $this->marital_status;
    }

    public function setDependents($dependent)
    {
        $this->dependents = $dependent;
        return $this;
    }

    public function getDependents()
    {
        return $this->dependents;
    }

    public function getQualifiedDependents()
    {
        $count = 0;
        foreach ($this->dependents as $dependent) {
            if($dependent['qualified'] == 'qualified')
            {
                $count++;
            }
        }

        return $count;
    }

    public function isNew()
    {
        return $this->flag_new;
    }

    public function setOld()
    {
        $this->flag_new = false;
    }

    public function isExempted()
    {
        return $this->exemption;
    }

    public function setExemption($exemption)
    {
        $this->exemption = $exemption;
    }


    public function getMonthlyRate()
    {
        switch($this->getPayPeriod()->getPaydays())
        {
            //Daily 
            case 312 : return $this->getPayRate() * 313/12;
                    break;

            //Weekly 
            case 52 : return $this->getPayRate() * 4;
                    break;

            //Semi Monthly
            case 24 : return $this->getPayRate() * 2;
                    break;

            //Monthly
            case 12 : 
            default: return $this->getPayRate();
                    break;
        }
    }

    public function getDailyRate()
    {
        switch($this->getPayPeriod()->getPaydays())
        {
            //Weekly
            case 52 : $computed = $this->getPayRate()/6;
                    break;

            //Semi Monthly
            case 24 : $computed = $this->getPayRate()/12;
                    break;

            //Monthly
            case 12 : 
            // default: $computed = $this->getPayRate() * 12/313;
                    break;

            //Daily
            case 312 : 
            default : $computed = $this->getPayRate();
                    break;
        }
        return floor($computed * 100) / 100;
    }

    public function getSalaryHistory()
    {
        return $this->salary_history;
    }

     public function setCashbondRate($rate)
    {
        $this->cashbond_rate = $rate;
        return $this;
    }

    public function getCashbondRate()
    {
        return $this->cashbond_rate;
    }

    public function getRateforPeriod($date)
    {
        foreach($this->salary_history as $history)
        {
            if($date > $history->getDateCreate()){
                return $history->getPay();
            }
        }

        return $this->pay_rate;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        $data->employee_id = $this->employee_code;
        $data->employment_status = $this->employment_status;
        //$data->profile = $this->profile->toData();
        $data->job_title = $this->job_title != null? $this->job_title->toData():'';
        $data->department = $this->department != null? $this->department->toData():'';
        $data->location = $this->location != null? $this->location->toData():'';
        $data->schedule = $this->schedule != null? $this->schedule->toData():'';

        return $data;
    }
}

