<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_holiday")
 */
class Holiday
{
    const TYPE_REGULAR = "Regular Holiday";
    const TYPE_SPECIAL = "Special Non-Working";
    
    use HasName;
    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="date") */
    protected $date;

    /** @ORM\Column(type="string", length = 20) */
    protected $type;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getDateDisplay()
    {
        return $this->date->format('m/d/Y');
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
        $this->dataTrackCreate($data);
        $this->dataHasName($data);
        return $data;
    }
}