<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasCode;
use Catalyst\CoreBundle\Template\Entity\HasNotes;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_leave")
 */

class Leave
{
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECT = 'Rejected';
    const STATUS_PENDING_REVIEW = 'Pending Review';
    const STATUS_REVIEWED = 'Reviewed';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;
    use HasNotes;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $approved_by;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\EmployeeLeaves")
     * @ORM\JoinColumn(name="emp_leave_id", referencedColumnName="id")
     */
    protected $emp_leave;

	/** @ORM\Column(type="datetime") */
	protected $date_filed;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_start;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_end;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $applied_leave_days;

    /** @ORM\Column(type="boolean") */
    protected $accept_sat;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_reviewed_hr;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_reviewed_dept_head;


	public function __construct()
    {
        $this->initTrackCreate();
        $this->setAcceptSat(false);
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->approved_by = $this->approved_by;
        $data->leave_name = $this->emp_leave->getLeaveType()->getName();
        $data->leave_id = $this->emp_leave->getLeaveType()->getID();
        $data->date_start = $this->date_start;
        $data->date_end = $this->date_end;
        $data->status = $this->status;
        return $data;
    }

    /**
     * Set approvedBy
     *
     * @param string $approvedBy
     *
     * @return Leave
     */
    public function setApprovedBy($approvedBy)
    {
        $this->approved_by = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return string
     */
    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    /**
     * Set dateFiled
     *
     * @param \DateTime $dateFiled
     *
     * @return Leave
     */
    public function setDateFiled($dateFiled)
    {
        $this->date_filed = $dateFiled;

        return $this;
    }

    /**
     * Get dateFiled
     *
     * @return \DateTime
     */
    public function getDateFiled()
    {
        return $this->date_filed;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Leave
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Leave
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * Set dateApproved
     *
     * @param \DateTime $dateApproved
     *
     * @return Leave
     */
    public function setDateApproved($dateApproved)
    {
        $this->date_approved = $dateApproved;

        return $this;
    }

    /**
     * Get dateApproved
     *
     * @return \DateTime
     */
    public function getDateApproved()
    {
        return $this->date_approved;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Leave
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return Leave
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
     * Set appliedLeaveDays
     *
     * @param integer $appliedLeaveDays
     *
     * @return Leave
     */
    public function setAppliedLeaveDays($appliedLeaveDays)
    {
        $this->applied_leave_days = $appliedLeaveDays;

        return $this;
    }

    /**
     * Get appliedLeaveDays
     *
     * @return integer
     */
    public function getAppliedLeaveDays()
    {
        return $this->applied_leave_days;
    }

    /**
     * Set empLeave
     *
     * @param \Hris\WorkforceBundle\Entity\EmployeeLeaves $empLeave
     *
     * @return Leave
     */
    public function setEmpLeave(\Hris\WorkforceBundle\Entity\EmployeeLeaves $empLeave = null)
    {
        $this->emp_leave = $empLeave;

        return $this;
    }

    /**
     * Get empLeave
     *
     * @return \Hris\WorkforceBundle\Entity\EmployeeLeaves
     */
    public function getEmpLeave()
    {
        return $this->emp_leave;
    }

    public function getEmpLeaveName()
    {
        return $this->emp_leave->getLeaveType()->getName();
    }

    /**
     * Set acceptSat
     *
     * @param boolean $acceptSat
     *
     * @return Leave
     */
    public function setAcceptSat($acceptSat)
    {
        $this->accept_sat = $acceptSat;

        return $this;
    }

    /**
     * Get acceptSat
     *
     * @return boolean
     */
    public function getAcceptSat()
    {
        return $this->accept_sat;
    }

    /**
     * Set date_reviewed_hr
     *
     * @param \DateTime $date_reviewed_hr
     *
     * @return Leave
     */
    public function setDateReviewedHR($date_reviewed_hr)
    {
        $this->date_reviewed_hr = $date_reviewed_hr;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateReviewedHR()
    {
        return $this->date_reviewed_hr;
    }

    /**
     * Set date
     *
     * @param \DateTime $date_reviewed_dept_head
     *
     * @return Leave
     */
    public function setDateReviewedDH($date_reviewed_dept_head)
    {
        $this->date_reviewed_dept_head = $date_reviewed_dept_head;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateReviewedDH()
    {
        return $this->date_reviewed_dept_head;
    }
}
