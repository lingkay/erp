<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_schedule")
 */
class PaySchedule
{
    use HasGeneratedID;
    use HasName;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\PayrollBundle\Entity\PayPeriod")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     */
    protected $period;

    /** @ORM\Column(type="json_array") */
    protected $start_end;


    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setPeriod(\Hris\PayrollBundle\Entity\PayPeriod $period)
    {
        $this->period = $period;
        return $this;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function setStartEnd($start_end)
    {
        $this->start_end = $start_end;
        return $this;
    }

    public function getStartEnd()
    {
        return $this->start_end;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);

        return $data;
    }
}
