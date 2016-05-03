<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasCode;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\MediaBundle\Template\Entity\HasUpload;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_reimbursement")
 */

class Reimbursement
{
    const STATUS_PENDING = 'Pending';
    const STATUS_APPROVED = 'Approved';
    const STATUS_REJECT = 'Reject';
    const STATUS_REVIEW = 'Reviewed';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;
    use HasUpload;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="\Catalyst\UserBundle\Entity\user")
     * @ORM\JoinColumn(name="approver_id", referencedColumnName="id")
     */
    protected $approved_by;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Request")
     * @ORM\JoinColumn(name="request_id", referencedColumnName="id")
     */
    protected $request;

	/** @ORM\Column(type="datetime") */
	protected $date_filed;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

	/** @ORM\Column(type="text") */
	protected $receipt_no;

	/** @ORM\Column(type="string") */
	protected $expense_type;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
	protected $cost;

	public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_PENDING;
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

    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
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

    public function setReceipt($receipt)
    {
        $this->receipt_no = $receipt;
        return $this;
    }

    public function getReceipt()
    {
        return $this->receipt_no;
    }

    public function setExpense($expense)
    {
        $this->expense_type = $expense;
        return $this;
    }

    public function getExpense()
    {
        return $this->expense_type;
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

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->receipt_no = $this->receipt_no;
        $data->expense_type = $this->expense_type;
        $data->cost = $this->cost;
        $data->approved_by = $this->approved_by;
        return $data;
    }

    /**
     * Set receiptNo
     *
     * @param string $receiptNo
     *
     * @return Reimbursement
     */
    public function setReceiptNo($receiptNo)
    {
        $this->receipt_no = $receiptNo;

        return $this;
    }

    /**
     * Get receiptNo
     *
     * @return string
     */
    public function getReceiptNo()
    {
        return $this->receipt_no;
    }

    /**
     * Set expenseType
     *
     * @param string $expenseType
     *
     * @return Reimbursement
     */
    public function setExpenseType($expenseType)
    {
        $this->expense_type = $expenseType;

        return $this;
    }

    /**
     * Get expenseType
     *
     * @return string
     */
    public function getExpenseType()
    {
        return $this->expense_type;
    }

    /**
     * Set request
     *
     * @param \Hris\WorkforceBundle\Entity\Request $request
     *
     * @return Reimbursement
     */
    public function setRequest(\Hris\WorkforceBundle\Entity\Request $request = null)
    {
        $this->request = $request;

        return $this;
    }

    /**
     * Get request
     *
     * @return \Hris\WorkforceBundle\Entity\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Set approvedBy
     *
     * @param \Catalyst\UserBundle\Entity\user $approvedBy
     *
     * @return Reimbursement
     */
    public function setApprovedBy(\Catalyst\UserBundle\Entity\user $approvedBy = null)
    {
        $this->approved_by = $approvedBy;

        return $this;
    }

    /**
     * Get approvedBy
     *
     * @return \Catalyst\UserBundle\Entity\user
     */
    public function getApprovedBy()
    {
        return $this->approved_by;
    }
}
