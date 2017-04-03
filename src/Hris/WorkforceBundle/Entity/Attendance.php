<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\MediaBundle\Template\Entity\HasUpload;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_attendance",uniqueConstraints={@ORM\UniqueConstraint(name="attendance_idx", columns={"employee_id", "date"})})
 */

class Attendance
{
	use HasGeneratedID;
	use TrackCreate;
    use HasUpload;

    //Marks an employee as present and calculates the late etc
    const STATUS_PRESENT = "Present";
    //Marks the employee absent for that day
    const STATUS_ABSENT = "Absent";
    //Marks the employee as on leave so as not to count as an absence
    const STATUS_PAIDLEAVE = "Paid Leave";
    //Marks the employee as on leave so as not to count as an absence
    const STATUS_UNPAIDLEAVE = "Unpaid Leave";
    //Marks the employee as present on a non working holiday
    const STATUS_HOLIDAYNONWORKING = "Non-Working Holiday";
    //Marks the employee as absent on a non working holiday
    const STATUS_ABSENTNONWORKING = "Absent on Non-Working Holiday";
    //Marks the employee as present on a non working holiday and rest day
    const STATUS_NONWORKINGRESTDAY = "Non-Working Holiday and Rest Day";
    //Marks the employee as present on a regular holiday
    const STATUS_HOLIDAY = "Holiday";
    //Marks the employee as present on a regular holiday and rest day
    const STATUS_HOLIDAYRESTDAY = "Holiday and Rest day";
    //Marks the employee as present on a double holiday
    const STATUS_DOUBLEHOLIDAY = "Double Holiday";
    //Marks the employee as present on a double holiday and rest day
    const STATUS_DOUBLEHOLIDAYRESTDAY = "Double Holiday and Rest day";
    //Marks the employee as present on a nonworking day according to his schedule
    const STATUS_NONWORKING = "Non-Working"; //
    //Marks the employee as Halfday
    const STATUS_HALFDAY = "Halfday";
    //Attendance Adjustment Status (Pending,Approved,Reject)
    const STATUS_DRAFT = "Draft";
    const STATUS_APPROVE = "Approved";
    const STATUS_REJECT = "Reject";
    const STATUS_REVIEW = "Review";

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee", inversedBy="attendance")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approver_id", referencedColumnName="id")
     */
    protected $approved_by;

    /** @ORM\Column(type="string", length=40) */
    protected $status;

	/** @ORM\Column(type="date") */
	protected $date;

	/** @ORM\Column(type="datetime", nullable=true) */
	protected $time_in;

	/** @ORM\Column(type="datetime", nullable=true) */
	protected $time_out;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $undertime;

	/** @ORM\Column(type="integer", nullable=true) */
	protected $late;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $overtime;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $adjustment_date;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $adjusted_time_in;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $adjusted_time_out;

    /** @ORM\Column(type="string", length=30, nullable=true)*/
    protected $adjustment_status;

    /** @ORM\Column(type="string", length=50, nullable=true)*/
    protected $reason;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $adjust_approved;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $overtime_date;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $overtime_pending;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $overtime_status;

    /** @ORM\Column(type="string", length=50, nullable=true)*/
    protected $overtime_reason;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $overtime_approved;

    /** @ORM\Column(type="string", length=10 , nullable=true) */
    protected $halfday;

	public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_PRESENT;
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

    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDateDisplay()
    {
        return $this->date->format('m/d/Y');
    }

    public function setTimeIn(DateTime $in)
    {
        $this->time_in = $in;
    	return $this;
    }

    public function getTimeIn()
    {
        if ($this->time_in == null)
        {
            return $this->time_in;
        }
        else
        {
            return $this->time_in->format('g:i A');
        }
    }

    public function setTimeOut(DateTime $out)
    {
    	$this->time_out = $out;
    	return $this;
    }

    public function getTimeOut()
    {
        if ($this->time_out == null)
        {
            return $this->time_out;
        }
        else
        {
            return $this->time_out->format('g:i A');
        }
    }

    public function setUnderTime($undertime)
    {
    	$this->undertime = $undertime;
    	return $this;
    }

    public function getUnderTime()
    {
    	return $this->undertime;
    }

    public function getUnderTimeDisplay()
    {
        return $this->undertime . ' Minute(s)';
    }

    public function setOvertime($overtime)
    {
        $this->overtime = $overtime;
        return $this;
    }

    public function getOvertime()
    {
        return $this->overtime;
    }

    public function getOvertimeDisplay()
    {
        return $this->overtime . ' Minute(s)';
    }

    public function setLate($late)
    {
    	$this->late = $late;
    	return $this;
    }

    public function getLate()
    {
    	return $this->late;
    }

    public function getLateDisplay()
    {
        return $this->late . ' Minute(s)';
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setAdjustTimeIn(DateTime $time_in)
    {
        $this->adjusted_time_in = $time_in;
        return $this;
    }

    public function getAdjustTimeIn()
    {
        return $this->adjusted_time_in;
    }

    public function setAdjustTimeOut(DateTime $time_out)
    {
        $this->adjusted_time_out = $time_out;
        return $this;
    }
    
    public function getAdjustTimeOut()
    {
        return $this->adjusted_time_out;
    }

    public function setAdjustmentStatus($status)
    {
        $this->adjustment_status = $status;
        return $this;
    }

    public function getAdjustmentStatus()
    {
        return $this->adjustment_status;
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

    public function setAdjustmentDate(DateTime $date)
    {
        $this->adjustment_date = $date;
        return $this;
    }

    public function getAdjustmentDate()
    {
        return $this->adjustment_date;
    }

    public function setApprovedBy($user)
    {
        $this->approved_by = $user;
        return $this;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function setDateApproved(DateTime $date)
    {
        $this->adjust_approved = $date;
        return $this;
    }

    public function getDateApproved()
    {
        return $this->adjust_approved;
    }

    public function setOvertimeTemp($ot)
    {
        $this->overtime_pending = $ot;
        return $this;
    }

    public function getOvertimeTemp()
    {
        return $this->overtime_pending;
    }

    public function setOvertimeStatus($status)
    {
        $this->overtime_status = $status;
        return $this;
    }

    public function getOvertimeStatus()
    {
        return $this->overtime_status;
    }

    public function setOvertimeDate($date)
    {
        $this->overtime_date = $date;
        return $this;
    }

    public function getOvertimeDate()
    {
        return $this->overtime_date;
    }

    public function setOvertimeReason($reason)
    {
        $this->overtime_reason;
        return $this;
    }

    public function getOvertimeReason()
    {
        return $this->overtime_reason;
    }

    public function setOvertimeApproved($date)
    {
        $this->overtime_approved = $date;
        return $this;
    }

    public function getOvertimeApproved()
    {
        return $this->overtime_approved;
    }

    public function setHalfday($half)
    {   
        $this->halfday = $half;
        return $this;
    }

    public function isHalfday()
    {
        return $this->halfday;
    }
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $data->employee = $this->employee->toData();
        $data->date = $this->date;
        $data->time_in = $this->time_in;
        $data->time_out = $this->time_out;
        $data->undertime = $this->undertime;
        $data->late = $this->late;
        $data->status = $this->status;
        return $data;
    }
}
?>