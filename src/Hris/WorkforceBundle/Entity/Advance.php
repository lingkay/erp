<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_advance")
 */

class Advance
{
    const STATUS_PENDING = 'Pending';
    const STATUS_RELEASED = 'Released';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

	/** @ORM\Column(type="datetime") */
	protected $date_filed;

   /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="releaser_id", referencedColumnName="id")
     */
    protected $released_by;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_released;


    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
	protected $amount;

    /** @ORM\Column(type="boolean") */
    protected $flag_deducted;

    /** @ORM\Column(type="string", length=20) */
    protected $type;


	public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_PENDING;
        $this->flag_deducted = false;
        $this->date_filed = new DateTime();
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


    public function setDateFiled(DateTime $date)
    {
        $this->date_filed = $date;
        return $this;
    }

    public function getDateFiled()
    {
        return $this->date_filed;            
    }


    public function setDateReleased(DateTime $date)
    {
        $this->date_released = $date;
        return $this;
    }

    public function getDateReleased()
    {
        return $this->date_released;
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
        if($status == self::STATUS_RELEASED)
        {
            $this->setDateReleased(new DateTime());
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

    public function isDeducted()
    {
        return $this->flag_deducted;
    }

    public function setDeducted()
    {
        $this->flag_deducted = true;
        return $this;
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

    public function setReleasedBy(\Hris\WorkforceBundle\Entity\Employee $released_by = null)
    {
        $this->released_by = $released_by;

        return $this;
    }

    public function getReleasedBy()
    {
        return $this->released_by;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->amount = $this->amount;
        return $data;
    }
}
?>