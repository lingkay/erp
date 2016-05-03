<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Hris\AdminBundle\Entity\Benefit;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_employee_benefits")
 */
class EmployeeBenefits
{
    use HasGeneratedID;
    
    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Benefit")
     * @ORM\JoinColumn(name="benefit_id", referencedColumnName="id")
     */
    protected $benefit;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Leave\LeaveType")
     * @ORM\JoinColumn(name="leave_id", referencedColumnName="id")
     */
    protected $leave;


    public function __construct()
    {

    }

    public function toData()
    {
        $data = new stdClass();
       
        return $data;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return EmployeeBenefits
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
     * Set benefit
     *
     * @param \Hris\AdminBundle\Entity\Benefit $benefit
     *
     * @return EmployeeBenefits
     */
    public function setBenefit(\Hris\AdminBundle\Entity\Benefit $benefit = null)
    {
        $this->benefit = $benefit;

        return $this;
    }

    /**
     * Get benefit
     *
     * @return \Hris\AdminBundle\Entity\Benefit
     */
    public function getBenefit()
    {
        return $this->benefit;
    }

    /**
     * Set leave
     *
     * @param \Hris\AdminBundle\Entity\Leave\LeaveType $leave
     *
     * @return EmployeeBenefits
     */
    public function setLeave(\Hris\AdminBundle\Entity\Leave\LeaveType $leave = null)
    {
        $this->leave = $leave;

        return $this;
    }

    /**
     * Get leave
     *
     * @return \Hris\AdminBundle\Entity\Leave\LeaveType
     */
    public function getLeave()
    {
        return $this->leave;
    }
}
