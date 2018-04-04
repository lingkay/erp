<?php

namespace Hris\AdminBundle\Entity\Leave;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="leave_type")
 */
class LeaveType
{
    use HasGeneratedID;
    use TrackCreate;
    use HasName;
    use HasNotes;

    /** @ORM\Column(type="text", nullable=true) */
    protected $gender;

    /** @ORM\Column(type="text", nullable=true) */
    protected $emp_status;

   /** @ORM\Column(type="boolean", nullable=true) */
    protected $accrue_enabled;

    /** @ORM\Column(type="boolean") */
    protected $collectible;

    /** @ORM\Column(type="boolean") */
    protected $convertible_to_cash;

    /** @ORM\Column(type="float", nullable=true) */
    protected $accrue_count;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $accrue_rule;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $carried_enabled;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $carry_percentage;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $carry_period;

    /** @ORM\Column(type="float") */
    protected $leave_count;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $count_type;

    /** @ORM\Column(type="integer", nullable=true, nullable=true) */
    protected $service_months;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $payment_type;

    /** @ORM\Column(type="text", nullable=true) */
    protected $addtl_requirements;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        return $data;
    }

    public function setCollectible($bool)
    {
        $this->collectible = $bool;

        return $this;
    }

    public function setConvertibleToCash($bool)
    {
        $this->convertible_to_cash = $bool;

        return $this;
    }

    public function getCollectible()
    {
        return $this->collectible;
    }

    public function getConvertibleToCash()
    {
        return $this->convertible_to_cash;
    }

    /**
     * Set accrueEnabled
     *
     * @param boolean $accrueEnabled
     *
     * @return LeaveType
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
     * @return LeaveType
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
     * @return LeaveType
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
     * @return LeaveType
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
     * @return LeaveType
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
     * @return LeaveType
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
     * Set addtlRequirements
     *
     * @param string $addtlRequirements
     *
     * @return LeaveType
     */
    public function setAddtlRequirements($addtlRequirements)
    {
        $this->addtl_requirements = serialize($addtlRequirements);

        return $this;
    }

    /**
     * Get addtlRequirements
     *
     * @return string
     */
    public function getAddtlRequirements()
    {
        return unserialize($this->addtl_requirements);
    }

    /**
     * Set accrueRule
     *
     * @param string $accrueRule
     *
     * @return LeaveType
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
     * @return LeaveType
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
     * @return LeaveType
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

    /**
     * Set countType
     *
     * @param string $countType
     *
     * @return LeaveType
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
