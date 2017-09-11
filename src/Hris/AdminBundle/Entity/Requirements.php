<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_requirements")
 */
class Requirements
{
    use HasGeneratedID;
    use TrackCreate;
    use HasName;

    const TYPE_FILE = "File";
    const TYPE_IMAGE = "Image";
    const TYPE_NUMBER = "Number";
    const TYPE_TEXT = "Text";

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $req_type;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Leave\LeaveType")
     * @ORM\JoinColumn(name="leave_type_id", referencedColumnName="id")
     */
    protected $leave_type;

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

    /**
     * Set reqType
     *
     * @param string $reqType
     *
     * @return Requirements
     */
    public function setReqType($reqType)
    {
        $this->req_type = $reqType;

        return $this;
    }

    /**
     * Get reqType
     *
     * @return string
     */
    public function getReqType()
    {
        return $this->req_type;
    }

    /**
     * Set leaveType
     *
     * @param \Hris\AdminBundle\Entity\Leave\LeaveType $leaveType
     *
     * @return Requirements
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
}
