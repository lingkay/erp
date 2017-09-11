<?php

namespace Hris\RemunerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Hris\RemunerationBundle\Entity\Cashbond;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="rem_cashbondloan")
 */

class CashbondLoan
{
    const STATUS_DRAFT = 'Draft';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECT = 'Reject';

	use HasGeneratedID;
	use TrackCreate;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\RemunerationBundle\Entity\Cashbond")
     * @ORM\JoinColumn(name="cashbond_id", referencedColumnName="id")
     */
    protected $cashbond;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $amount;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="approver_id", referencedColumnName="id")
     */
    protected $approved_by;


	public function __construct($employee = null)
    {
        $this->employee = $employee;
        $this->initTrackCreate();
        $this->amount = 0.0;
        $this->status = self::STATUS_DRAFT;
    }

    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee)
    {
    	$this->employee = $employee;
    	return $this;
    }

    public function getEmployee()
    {
    	return $this->employee;
    }

    public function setCashbond(Cashbond $cashbond)
    {
        $this->cashbond = $cashbond;
        return $this;
    }

    public function getCashbond()
    {
        return $this->cashbond;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
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

    public function setApprovedBy(\Hris\WorkforceBundle\Entity\Employee $employee)
    {
        $this->approved_by = $employee;
        return $this;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->amount = $this->amount;
        
        return $data;
    }
}
?>