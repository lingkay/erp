<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_request")
 */
class Request
{
    use HasGeneratedID;
    use TrackCreate;
    use HasNotes;

    const STATUS_PENDING    =   'Pending';
    const STATUS_APPROVE    =   'Approved';
    const STATUS_REJECT     =   'Reject';
    const STATUS_PRINT      =   'Print';

    const TYPE_REIMBURSE    =   'Reimbursement';
    const TYPE_COE          =   'Certificate of Employment';
    const TYPE_PROP         =   'Property/Item';
    const TYPE_RESIGN       =   'Resignation';

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $approved_by;

    /** @ORM\Column(type="string", length=30, nullable=true) */
    protected $request_type;

    /** @ORM\Column(type="datetime") */
    protected $date_filed;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    public function __construct()
    {
        $this->initTrackCreate();
        // $this->status = self::STATUS_PENDING;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        return $data;
    }

    /**
     * Set approvedBy
     *
     * @param string $approvedBy
     *
     * @return Request
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
     * Set requestType
     *
     * @param string $requestType
     *
     * @return Request
     */
    public function setRequestType($requestType)
    {
        $this->request_type = $requestType;

        return $this;
    }

    /**
     * Get requestType
     *
     * @return string
     */
    public function getRequestType()
    {
        return $this->request_type;
    }

    /**
     * Set dateFiled
     *
     * @param \DateTime $dateFiled
     *
     * @return Request
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
     * Set dateApproved
     *
     * @param \DateTime $dateApproved
     *
     * @return Request
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
     * @return Request
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
     * @return Request
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
}
