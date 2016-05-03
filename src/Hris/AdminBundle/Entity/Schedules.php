<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use DateTime;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_schedule")
 */
class Schedules
{
    use HasGeneratedID;
    use HasName;
    use TrackCreate;

    /** @ORM\Column(type="datetime") */
    protected $start;

    /** @ORM\Column(type="datetime") */
    protected $end;

    /** @ORM\Column(type="string", length=10) */
    protected $day_start;

    /** @ORM\Column(type="string", length=10) */
    protected $day_end;

    /** @ORM\Column(type="integer") */
    protected $grace_period;

    /** @ORM\Column(type="integer") */
    protected $half_day;

    /** @ORM\Column(type="string", length=15, nullable=true) */
    protected $type;

    /** @ORM\Column(type="float", nullable=true) */
    protected $required_hours;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setStart(DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    public function getStart()
    {
        if ($this->start == null)
        {
            return $this->start;            
        }
        else
        {
            return $this->start->format('g:i A');
        }
    }

    public function setEnd(DateTime $end)
    {
        $this->end = $end;
        return $this;
    }

    public function getEnd()
    {
        if ($this->end == null)
        {
            return $this->end;            
        }
        else
        {
            return $this->end->format('g:i A');
        }
    }

    public function setDayStart($start)
    {
        $this->day_start = $start;
        return $this;
    }

    public function getDayStart()
    {
        return $this->day_start;
    }

    public function setDayEnd($end)
    {
        $this->day_end = $end;
        return $this;
    }

    public function getDayEnd()
    {
        return $this->day_end;
    }

    public function getDisplaySchedule()
    {
        return $this->start->format('g:i A').' - '.$this->end->format('g:i A');
    }

    public function setGracePeriod($grace)
    {
        $this->grace_period = $grace;
        return $this;
    }

    public function getGracePeriod()
    {
        return $this->grace_period;
    }

    public function getGracePeriodText()
    {
        if ($this->grace_period == 1) 
        {
            return $this->grace_period.' minute';
        }
        elseif ($this->grace_period > 2) 
        {
            return $this->grace_period.' minutes';
        }
        elseif ($this->grace_period == 0) 
        {
            return 'Not set';
        }
    }

    public function setHalfday($halfday)
    {
        $this->half_day = $halfday;
        return $this;
    }

    public function getHalfday()
    {
        return $this->half_day;
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

    public function getTypeLabel()
    {
        if ($this->type == 'semi-flexi') {
            return 'Semi-flexible';
        } elseif ($this->type == 'flexi') {
            return 'Flexible';
        } else {
            return 'Fixed';
        }
    }

    public function setRequiredHours($required_hours)
    {
        $this->required_hours = $required_hours;
        return $this;
    }

    public function getRequiredHours()
    {
        return $this->required_hours;
    }
    
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);

        return $data;
    }
}
