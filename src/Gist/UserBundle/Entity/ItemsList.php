<?php

namespace Gist\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_items_list")
 */

class ItemsList
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150) */
    protected $name;

    /** @ORM\Column(type="string", length=150) */
    protected $serial_number;



    public function __construct()
    {
        $this->initTrackCreate();
    }



    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setSerialNumber($serial_number)
    {
        $this->serial_number = $serial_number;

        return $this;
    }

    public function getSerialNumber()
    {
        return $this->serial_number;
    }

    public function getFormattedName()
    {
        return $this->name . ' - ' . $this->serial_number;
    }



    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
//        $this->dataHasCode($data);

//        $data->emp_name = $this->employee->getDisplayName();
//        $data->emp_id = $this->employee->getID();
//        $data->approved_by = $this->approved_by;
//        $data->leave_name = $this->emp_leave->getLeaveType()->getName();
//        $data->leave_id = $this->emp_leave->getLeaveType()->getID();
//        $data->date_start = $this->date_start;
//        $data->date_end = $this->date_end;
//        $data->status = $this->status;
        return $data;
    }

}
