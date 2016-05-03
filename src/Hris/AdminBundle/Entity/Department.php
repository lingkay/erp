<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_department")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="name", column=@ORM\Column(type="string", name="name", length=255, unique=true, nullable=false)),
 * })
 */
class Department
{
    use HasGeneratedID;
    use HasName;
    use TrackCreate;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="dept_head_id", referencedColumnName="id")
     */
    protected $dept_head;

    /**
     * @ORM\ManyToOne(targetEntity="Department", inversedBy="subdepartment")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    /** @ORM\OneToMany(targetEntity="Department", mappedBy="parent") */
    protected $subdepartment;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->parent = null;
        $this->subdepartment = new ArrayCollection();
    }

    public function setDeptHead(\Hris\WorkforceBundle\Entity\Employee $deptHead = null)
    {
        $this->dept_head = $deptHead;

        return $this;
    }

    public function getDeptHead()
    {
        return $this->dept_head;
    }

    public function getDeptHeadName()
    {
        if($this->dept_head == null)
            return 'N/A';

        return $this->dept_head->getDisplayName();
    }

    public function setParent($department)
    {
        $this->parent = $department;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addSubDepartment(Department $department)
    {
        $department->setParent($this);
        $this->subdepartment->add($department);
        return $this;
    }

    public function getSubDepartments()
    {
        return $this->subdepartment;
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
