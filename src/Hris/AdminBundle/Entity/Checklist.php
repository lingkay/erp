<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_checklist")
 */
class Checklist
{
    use HasGeneratedID;
    use HasName;
    use TrackCreate;
    use HasNotes;


    /** @ORM\OneToMany(targetEntity="\Hris\WorkforceBundle\Entity\EmployeeChecklist", mappedBy="checklist", cascade={"persist"}) */
    protected $employee_checklist;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->initHasName();
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
