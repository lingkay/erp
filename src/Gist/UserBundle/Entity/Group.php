<?php

namespace Gist\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\Group as BaseGroup;
use FOS\UserBundle\Model\GroupInterface;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_group")
 */
class Group extends BaseGroup implements GroupInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    protected $users;

    /**
     * @ORM\ManyToOne(targetEntity="Department")
     * @ORM\JoinColumn(name="department_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="variants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     **/
    protected $parent;

    /** @ORM\OneToMany(targetEntity="Group", mappedBy="parent") */
    protected $variants;

    /** @ORM\Column(type="text") */
    protected $access;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $job_description;

    //parent&child
    public function setParent($group)
    {
        $this->parent = $group;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function setJobDescription($job_description)
    {
        $this->job_description = $job_description;
        return $this;
    }

    public function getJobDescription()
    {
        return $this->job_description;
    }

//    public function addVariant(Group $group)
//    {
//        $group->setParent($this);
//        $this->variants->add($group);
//        return $this;
//    }

    public function __construct($name, $roles = array())
    {
        parent::__construct($name, $roles);
        $this->roles = array();
    }

    public function clearAccess()
    {
        $access = array();
        $this->access = serialize($access);
    }

    public function addAccess($key)
    {
        $access = $this->getAccess();
        $access[$key] = true;

        $this->access = serialize($access);

        return $this;
    }

    public function removeAccess($key)
    {
        $access = $this->getAccess();
        $access[$key] = false;

        $this->access = serialize($access);

        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function getUserCount()
    {
        return count($this->users);
    }

    public function getVariantCount()
    {
        return count($this->variants);
    }

    public function getVariants()
    {
        return $this->variants;
    }

    public function getRoles()
    {
        return array();
    }

    //DEPT
    public function setDepartment($department)
    {
        $this->department = $department;
        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function getDepartmentName()
    {
        if ($this->department != null)
        {
            return $this->department->getDepartmentName();
        }
        else
        {

        }

    }

    public function getAccess()
    {
        $unser = unserialize($this->access);
        if ($unser === false)
            return array();

        if (!is_array($unser))
            return array();

        return $unser;
    }

    public function hasAccess($key)
    {   
        // check if it's id = 1, that's the super admin
        // if ($this->getID() == 1)
        //     return true;

        $access = $this->getAccess();

        if (!isset($access[$key]))
            return false;

        if ($access[$key])
            return true;

        return false;
    }

    public function toData($detailed = true)
    {
        $data = new stdClass();
        $data->id = $this->id;
        if ($detailed)
            $data->access = $this->getAccess();

        return $data;
    }
}

