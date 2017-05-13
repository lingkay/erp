<?php

namespace Gist\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\GroupInterface;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_user")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToMany(targetEntity="Group", inversedBy="users")
     * @ORM\JoinTable(name="user_usergroup")
     * @ORM\OrderBy({"name" = "ASC"})
     */
    protected $groups;





    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $name;

    protected $acl_cache;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
    *
    * @ORM\Column(type="boolean") 
    */
    protected $flag_emailnotify;

    public function __construct()
    {
        parent::__construct();
        $this->roles = array();
        $this->groups = new ArrayCollection();
        $this->acl_cache = array();
        $this->flag_emailnotify = true;
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    
    public function addGroup(GroupInterface $role)
    {
        $this->groups->add($role);
        return $this;
    }

    public function clearGroups()
    {
        $this->groups->clear();
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getGroups()
    {
        return $this->groups;
    }


    public function getGroupsText()
    {
        $groups = array();
        foreach ($this->groups as $g)
            $groups[] = $g->getName();
        return implode(', ', $groups);
    }

    public function getLastLoginText()
    {
        if ($this->getLastLogin() == null)
            return 'Never';
        return $this->getLastLogin()->format('M d, Y - H:m:s');
    }

    public function getEnabledText()
    {
        if ($this->enabled)
            return 'Enabled';
        return 'Disabled';
    }

    public function hasAccess($acl_key)
    {
        // DEBUG: allow all for admin user
        if ($this->getUsername() == 'admin')
            return true;

        // check acl cache
        if (isset($this->acl_cache[$acl_key]))
            return $this->acl_cache[$acl_key];

        // go through all groups and check
        foreach ($this->groups as $group)
        {
            if ($group->hasAccess($acl_key))
            {
                $this->acl_cache[$acl_key] = true;
                return true;
            }
        }

        // no access
        $this->acl_cache[$acl_key] = false;
        return false;
    }

     public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function isEmailNotify()
    {
        return $this->flag_emailnotify;
    }

    public function setEmailNotify($flag)
    {
        $this->flag_emailnotify = $flag;
        return $this;
    }



    public function toData()
    {
        $groups = array();
        foreach ($this->groups as $group)
            $groups[] = $group->toData(false);

        $data = new stdClass();
        $data->id = $this->id;
        $data->username = $this->username;
        $data->email = $this->email;
        $data->enabled = $this->enabled;
        $data->groups = $groups;

        return $data;
    }
}
