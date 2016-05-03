<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\MediaBundle\Template\Entity\HasUpload;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_resign")
 */

class Resign
{
	use HasGeneratedID;
	use TrackCreate;
	use HasUpload;

	const STATUS_PENDING = 'Pending';
	const STATUS_ACCEPT = 'Approved';
	const STATUS_REJECT = 'Reject';

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="\Catalyst\UserBundle\Entity\User")
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

	/** @ORM\Column(type="string", nullable=true) */
	protected $status;

	/** @ORM\Column(type="datetime", nullable=true) */
	protected $date_approved;
	
	public function __construct()
	{
		$this->initTrackCreate();
		$this->status = self::STATUS_PENDING;
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

	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}

	public function getStatus()
	{
		return $this->status;
	}

	public function setApprovedBy($employee)
	{
		$this->approved_by = $employee;
		return $this;
	}

	public function getApprovedBy()
	{
		return $this->approved_by;
	}

    public function getDepartmentDisplay()
    {
        return $this->employee->getDepartment()->getName();
    }

    public function getSupervisor()
    {
    	if($this->employee->getSupervisor() == NULL)
    	{
    		return "Not Available";
    	}
    	else
    	{
    		return $this->employee->getSupervisor();
    	}
    }

    public function getSupervisorDisplayName()
    {
    	if($this->employee->getSupervisor() == NULL)
    	{
    		return "Not Available";
    	}
    	else
    	{
    		return $this->employee->getSupervisor()->getDisplayName();
    	}
    }

    public function setRequest($request)
    {
    	$this->request = $request;
    	return $this;
    }

    public function getRequest()
    {
    	return $this->request;
    }

	public function toData()
	{
		$data = new \stdClass();
		$this->dataHasGeneratedID($data);
		$this->dataTrackCreate($data);

		$data->emp_name = $this->employee->getDisplayName();
		$data->date_filed = $this->date_filed->format('m/d/Y');
		$data->status = $this->status;

		$data->date_approved = $this->date_approved;
		$data->approved_by = $this->approved_by;

		return $data;
	}
}
?>