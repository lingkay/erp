<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Hris\AdminBundle\Entity\Benefit;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_employee_leaves")
 */
class EmployeeLeaves
{
    use HasGeneratedID;
    
    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Leave\LeaveType")
     * @ORM\JoinColumn(name="leave_id", referencedColumnName="id")
     */
    protected $leave_type;

    /** @ORM\Column(type="float") */ 
    protected $avail_leaves;

    /** @ORM\Column(type="integer") */ 
    protected $leave_year;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $approved_count;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $pending_count;

    /** @ORM\Column(type="integer", nullable=true) */ 
    protected $used_count;

    /** @ORM\Column(type="float", nullable=true) */ 
    protected $accumulated_leave;


    public function __construct()
    {

    }

    public function toData()
    {
        $data = new stdClass();
       
        return $data;
    }

    /**
     * Set availLeaves
     *
     * @param float $availLeaves
     *
     * @return EmployeeLeaves
     */
    public function setAvailLeaves($availLeaves)
    {
        $this->avail_leaves = $availLeaves;

        return $this;
    }

    /**
     * Get availLeaves
     *
     * @return float
     */
    public function getAvailLeaves()
    {
        return $this->avail_leaves;
    }

    /**
     * Set leaveYear
     *
     * @param integer $leaveYear
     *
     * @return EmployeeLeaves
     */
    public function setLeaveYear($leaveYear)
    {
        $this->leave_year = $leaveYear;

        return $this;
    }

    /**
     * Get leaveYear
     *
     * @return integer
     */
    public function getLeaveYear()
    {
        return $this->leave_year;
    }

    /**
     * Set approvedCount
     *
     * @param integer $approvedCount
     *
     * @return EmployeeLeaves
     */
    public function setApprovedCount($approvedCount)
    {
        $this->approved_count = $approvedCount;

        return $this;
    }

    /**
     * Get approvedCount
     *
     * @return integer
     */
    public function getApprovedCount()
    {
        return $this->approved_count;
    }

    /**
     * Set usedCount
     *
     * @param integer $usedCount
     *
     * @return EmployeeLeaves
     */
    public function setUsedCount($usedCount)
    {
        $this->used_count = $usedCount;

        return $this;
    }

    /**
     * Get usedCount
     *
     * @return integer
     */
    public function getUsedCount()
    {
        return $this->used_count;
    }

    /**
     * Set accumulatedLeave
     *
     * @param float $accumulatedLeave
     *
     * @return EmployeeLeaves
     */
    public function setAccumulatedLeave($accumulatedLeave)
    {
        $this->accumulated_leave = $accumulatedLeave;

        return $this;
    }

    /**
     * Get accumulatedLeave
     *
     * @return float
     */
    public function getAccumulatedLeave()
    {
        return $this->accumulated_leave;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return EmployeeLeaves
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
     * Set leaveType
     *
     * @param \Hris\AdminBundle\Entity\Leave\LeaveType $leaveType
     *
     * @return EmployeeLeaves
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
     * Set pendingCount
     *
     * @param integer $pendingCount
     *
     * @return EmployeeLeaves
     */
    public function setPendingCount($pendingCount)
    {
        $this->pending_count = $pendingCount;

        return $this;
    }

    /**
     * Get pendingCount
     *
     * @return integer
     */
    public function getPendingCount()
    {
        return $this->pending_count;
    }
}
