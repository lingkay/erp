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
     * @ORM\ManyToMany(targetEntity="User", mappedBy="groups")
     */
    protected $users;

    /** @ORM\Column(type="text") */
    protected $access;


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

    public function getRoles()
    {
        return array();
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

