<?php

namespace Gist\LogBundle\Entity;

use DateTime;
use Gist\UserBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="log_entry")
 */
class LogEntry
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="datetime") */
    protected $date_in;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    protected $user;

    /** @ORM\Column(type="string", length=100) */
    protected $action_id;
    
    /** @ORM\Column(type="string", length=200) */
    protected $description;

    /** @ORM\Column(type="string", length=50) */
    protected $type;

    /** @ORM\Column(type="json_array") */
    protected $data;

    public function __construct()
    {
        $this->date_in = new DateTime();
    }

    public function setUser(User $user)
    {
        $this->user = $user;
        return $this;
    }

    public function setActionID($action_id)
    {
        $this->action_id = $action_id;
        return $this;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getDateCreate()
    {
        return $this->date_in;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function getActionID()
    {
        return $this->action_id;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getData()
    {
        return $this->data;
    }
}
