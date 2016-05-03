<?php

namespace Hris\RemunerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasCode;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\MediaBundle\Template\Entity\HasUpload;
use Catalyst\CoreBundle\Template\Entity\HasNotes;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="rem_loans")
 */

class Loan
{
    const STATUS_PENDING = 'Pending';
    const STATUS_PAID = 'Paid';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECT = 'Reject';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;
    use HasUpload;
    use HasNotes;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="approver_id", referencedColumnName="id")
     */
    protected $approved_by;

	/** @ORM\Column(type="datetime") */
	protected $date_filed;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

    /** @ORM\OneToMany(targetEntity="LoanPayment", mappedBy="loan") */
    protected $payments;

	/** @ORM\Column(type="text") */
	protected $type;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
	protected $cost;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $paid;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $balance;

    /** @ORM\Column(type="boolean") */
    protected $flag_auto;


	public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_PENDING;
        $this->payments = new ArrayCollection();
        $this->cost = 0.0;
        $this->paid = 0.0;
        $this->balance = 0.0;
        $this->flag_auto = false;
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

    public function getDepartment()
    {
        return $this->employee->getDepartment()->getName();
    }

    public function setDateFiled(DateTime $date)
    {
        $this->date_filed = $date;
        return $this;
    }

    public function getDateFiled()
    {
        return $this->date_filed;
    }

    public function getDateFiledDisplay()
    {
        return $this->date_filed->format('m/d/Y');
    }

    public function setDateApproved(DateTime $date)
    {
        $this->date_approved = $date;
        return $this;
    }

    public function getDateApproved()
    {
        return $this->date_approved;            
    }

    public function getDateApprovedDisplay()
    {
        if($this->date_approved != null)
        {
            return $this->date_approved->format('m/d/Y');            
        } 
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }

    public function setPaid($paid)
    {
        $this->paid = $paid;
        return $this;
    }

    public function getPaid()
    {
        return $this->paid;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        if($status == self::STATUS_APPROVED)
        {
            $this->setDateApproved(new DateTime());
        }
        
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDisplayStatus()
    {
        return ucfirst($this->status);
    }

    public function setApprovedBy(\Hris\WorkforceBundle\Entity\Employee $approvedBy = null)
    {
        $this->approved_by = $approvedBy;

        return $this;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->type = $this->type;
        $data->cost = $this->cost;
        $data->approved_by = $this->approved_by;
        return $data;
    }
}
?>