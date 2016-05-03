<?php

namespace Hris\MemoBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_memo")
 */
class Memo
{
    
    /* ATTENDANCE TYPES */
    const TYPE_TARDINESS = "Tardiness";
    const TYPE_PROMOTION = "Promotion";
    const TYPE_REGULARIZATION = "Regularization";
    const TYPE_VIOLATION = "Violation";
    const TYPE_DISCIPLINARY = "Disciplinary";
    const TYPE_ALL = "All";

    const STATUS_DRAFT = "Draft";
    const STATUS_FORREVIEW = "For Review";
    const STATUS_REVIEWED = "Reviewed";
    const STATUS_APPROVED = "Approved";
    const STATUS_NOTED = "Noted";
    const STATUS_SENT = "Sent";


    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="reviewed_id", referencedColumnName="id")
     */
    protected $reviewed_by;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="approved_id", referencedColumnName="id")
     */
    protected $approved_by;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="noted_id", referencedColumnName="id")
     */
    protected $noted_by;

    /** @ORM\Column(type="string", length=30) */
    protected $type;


    /** @ORM\Column(type="string", length=30) */
    protected $status;

    /** @ORM\Column(type="text") */
    protected $content;

    /** @ORM\Column(type="datetime") */
    protected $date_issued;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_DRAFT;

    }

    public function getDateIssued()
    {
        return $this->date_issued;
    }

    public function getDateIssuedFormatted()
    {
        return $this->date_issued->format('m/d/Y');;
    }

    public function setDateIssued($dateIssued)
    {
        $this->date_issued = $dateIssued;
        return $this;
    }

    public function getUserCreateName()
    {
        return $this->getUserCreate()->getName();
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

    public function getEmployeeName()
    {
        return $this->employee->getDisplayName();
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

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setContent($content)
    {
        //change this for JSON
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        //change this for json_decode(json)
        return $this->content;
    }

    public function setReviewedBy($employee)
    {
        $this->reviewed_by = $employee;
        return $this;
    }

    public function getReviewedBy()
    {
        return $this->reviewed_by;
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

    public function setNotedBy($employee)
    {
        $this->noted_by = $employee;
        return $this;
    }

    public function getNotedBy()
    {
        return $this->noted_by;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $data->type = $this->type;
        $data->content = $this->content;
        return $data;
    }

    
}
