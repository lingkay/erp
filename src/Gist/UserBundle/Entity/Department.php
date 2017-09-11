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
 * @ORM\Table(name="user_department")
 */

class Department
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=50) */
    protected $department_name;

    /**
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;




    public function __construct()
    {
        $this->initTrackCreate();
//        $this->setAcceptSat(false);
    }



    public function setDepartmentName($department_name)
    {
        $this->department_name = $department_name;

        return $this;
    }

    public function getDepartmentName()
    {
        return $this->department_name;
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
