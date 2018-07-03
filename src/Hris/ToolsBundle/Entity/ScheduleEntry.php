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

    /** @ORM\Column(type="time", nullable=true) */
    protected $time;

    /** @ORM\Column(type="time", nullable=true) */
    protected $time_in;

    /** @ORM\Column(type="time", nullable=true) */
    protected $time_out;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\Areas")
     * @ORM\JoinColumn(name="other_area_id", referencedColumnName="id")
     */
    protected $other_area;

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

    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    public function getTime()
    {
        return $this->time;
    }

    /**
     * @return \DateTime
     */
    public function getTimeIn()
    {
        return $this->time_in;
    }

    /**
     * @param mixed $time_in
     *
     * @return ScheduleEntry
     */
    public function setTimeIn($time_in)
    {
        $this->time_in = $time_in;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getTimeOut()
    {
        return $this->time_out;
    }

    /**
     * @param mixed $time_out
     *
     * @return ScheduleEntry
     */
    public function setTimeOut($time_out)
    {
        $this->time_out = $time_out;

        return $this;
    }

    public function setOtherArea($other_area)
    {
        $this->other_area = $other_area;
        return $this;
    }

    public function getOtherArea()
    {
        return $this->other_area;
    }
}
