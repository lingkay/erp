<?php

namespace Gist\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\PurchasingBundle\Entity\Supplier;
use Gist\ContactBundle\Template\Entity\HasPhones;
use Gist\ContactBundle\Entity\Phone;

/**
 * @ORM\Entity
 * @ORM\Table(name="cnt_contact_person")
 */
class ContactPerson
{

    use HasGeneratedID;
    use HasPhones;

    /** @ORM\Column(type="string", length=150) */
    protected $first_name;

    /** @ORM\Column(type="string", length=150) */
    protected $middle_name;

    /** @ORM\Column(type="string", length=150) */
    protected $last_name;

    /** @ORM\Column(type="string", length=150) */
    protected $email;

    /** @ORM\Column(type="boolean") */
    protected $is_primary;


    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->is_primary = false;
    }


    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setIsPrimary($pri = true)
    {
        $this->is_primary = $pri;
        return $this;
    }

    public function getIsPrimary()
    {
        return $this->is_primary;
    }

    public function getChecked()
    {
        if ($this->is_primary == true)
        {
            $checked = "checked";         
        }
        else
        {
            $checked = "";         
        }
        return $checked;
    }

    public function toData()
    {
        $data = new \stdClass();

        // $this->dataHasGeneratedID($data);
        // $this->dataTrackCreate($data);

        $data->id = $this->id;
        $data->first_name = $this->first_name;
        $data->middle_name = $this->middle_name;
        $data->last_name = $this->last_name;
        $data->email = $this->email;
        $data->checked = $this->getChecked();

        return $data;
    }
}
