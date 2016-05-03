<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\CoreBundle\Template\Entity\HasNotes;
use Catalyst\MediaBundle\Template\Entity\HasUpload;

use Hris\AdminBundle\Entity\Checklist;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_employee_checklist")
 */
class EmployeeChecklist
{
    use HasUpload;
    use HasNotes;

    const STATUS_RECEIVED = 'Received';
    const STATUS_PENDING = 'Pending';

    /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Profile", inversedBy="employee_checklist")
     * @ORM\JoinColumn(name="profile_id", referencedColumnName="id", nullable=false)
     */
    protected $profile;

   /**
     * @ORM\Id()
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Checklist", inversedBy="employee_checklist")
     * @ORM\JoinColumn(name="checklist_id", referencedColumnName="id", nullable=false)
     */
    protected $checklist;

    /** @ORM\Column(type="string",length=20) */
    protected $status;

    /** @ORM\Column(type="date", nullable=true) */
    protected $date_received;


    public function __construct($profile,$checklist)
    {
        $this->profile = $profile;
        $this->checklist = $checklist;
        $this->status = self::STATUS_PENDING;
    }

    public function setProfile(Profile $profile)
    {
        $this->profile = $profile;
        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function setChecklist($checklist)
    {
        $this->checklist = $checklist;
        return $this;
    }

    public function getChecklist()
    {
        return $this->checklist;
    }

    public function setDateReceived($date_received)
    {
        $this->date_received = $date_received;
        return $this;
    }

    public function getDateReceived()
    {
        return $this->date_received;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setSent($sent)
    {
        $this->sent = $sent;
        return $this;
    }

    public function isSent()
    {
        return $this->sent;
    }


    public function toData()
    {

        $data = new stdClass();
       

        return $data;
    }
}
