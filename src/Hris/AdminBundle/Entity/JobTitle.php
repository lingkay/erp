<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_job_title")
 */
class JobTitle
{
    const TYPE_RANK = "Rank";
    const TYPE_MANAGEMENT = "Management";

    use HasGeneratedID;
    use HasName;
    use HasNotes;
    use TrackCreate;


    /** @ORM\Column(type="string", length=80) */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Department")
     * @ORM\JoinColumn(name="dept_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity="JobTitle", inversedBy="subordinates")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    /** @ORM\OneToMany(targetEntity="JobTitle", mappedBy="parent") */
    protected $subordinates;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->initHasName();
        $this->initHasNotes();
        $this->type = self::TYPE_RANK;
        $this->parent = null;
        $this->subordinates = new ArrayCollection();
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this;
    }

    public function setDepartment(\Hris\AdminBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setParent(JobTitle $jobTitle)
    {
        $this->parent = $jobTitle;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addSubordinate(JobTitle $jobTitle)
    {
        $jobTitle->setParent($this);
        $this->subordinates->add($jobTitle);
        return $this;
    }

    public function getSubordinates()
    {
        return $this->subordinates;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);
        $this->dataHasNotes($data);

        return $data;
    }

   
}
