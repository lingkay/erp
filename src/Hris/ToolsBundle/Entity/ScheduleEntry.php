<?php

namespace Hris\ToolsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="tools_schedule_entry")
 */
class ScheduleEntry
{
    const TYPE_WORK = 'Work';
    const TYPE_VACATION_LEAVE = 'Vacation Leave';
    const TYPE_SICK_LEAVE = 'Sick Leave';
    const TYPE_DAY_OFF = 'Day-off';
    const TYPE_TRAINING = 'Training';
    const TYPE_OFFICE = 'Office';
    const TYPE_OTHER_AREA = 'Other Area';

    use HasGeneratedID;

    /**
     * @ORM\ManyToOne(targetEntity="Schedule")
     * @ORM\JoinColumn(name="schedule_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $schedule;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\POSLocations")
     * @ORM\JoinColumn(name="pos_location_id", referencedColumnName="id")
     */
    protected $pos_location;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=50) */
    protected $type;

    public function __construct()
    {

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

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }

    public function setSchedule($schedule = null)
    {
        $this->schedule = $schedule;

        return $this;
    }

    public function getSchedule()
    {
        return $this->schedule;
    }

    public function setPOSLocation($pos_location)
    {
        $this->pos_location = $pos_location;

        return $this;
    }

    public function getPOSLocation()
    {
        return $this->pos_location;
    }

    public function setEmployee($user)
    {
        $this->employee = $user;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }
}
