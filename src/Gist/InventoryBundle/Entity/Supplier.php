<?php

namespace Gist\InventoryBundle\Entity;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Doctrine\ORM\Mapping as ORM;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_supplier")
 */
class Supplier
{
    use HasGeneratedID;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $type;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $last_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $middle_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $tin;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $category;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $tax;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $shipment_period;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $contact_person;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $telephone;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $mobile;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $fax;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $website;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $status;



    public function __construct()
    {
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

    public function toData()
    {
        $data = new stdClass();
        $this->dataHasGeneratedID($data);
        $data->name = $this->name;

        return $data;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Supplier
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Supplier
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Supplier
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Supplier
     */
    public function setMiddleName($middleName)
    {
        $this->middle_name = $middleName;

        return $this;
    }

    /**
     * Get middleName
     *
     * @return string
     */
    public function getMiddleName()
    {
        return $this->middle_name;
    }

    /**
     * Set tin
     *
     * @param string $tin
     *
     * @return Supplier
     */
    public function setTIN($tin)
    {
        $this->tin = $tin;

        return $this;
    }

    /**
     * Get tin
     *
     * @return string
     */
    public function getTIN()
    {
        return $this->tin;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return Supplier
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set tax
     *
     * @param string $tax
     *
     * @return Supplier
     */
    public function setTax($tax)
    {
        $this->tax = $tax;

        return $this;
    }

    /**
     * Get tax
     *
     * @return string
     */
    public function getTax()
    {
        return $this->tax;
    }

    /**
     * Set shipmentPeriod
     *
     * @param string $shipmentPeriod
     *
     * @return Supplier
     */
    public function setShipmentPeriod($shipmentPeriod)
    {
        $this->shipment_period = $shipmentPeriod;

        return $this;
    }

    /**
     * Get shipmentPeriod
     *
     * @return string
     */
    public function getShipmentPeriod()
    {
        return $this->shipment_period;
    }

    /**
     * Set contactPerson
     *
     * @param string $contactPerson
     *
     * @return Supplier
     */
    public function setContactPerson($contactPerson)
    {
        $this->contact_person = $contactPerson;

        return $this;
    }

    /**
     * Get contactPerson
     *
     * @return string
     */
    public function getContactPerson()
    {
        return $this->contact_person;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     *
     * @return Supplier
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get telephone
     *
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return Supplier
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Supplier
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Supplier
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Supplier
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set website
     *
     * @param string $website
     *
     * @return Supplier
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string
     */
    public function getWebsite()
    {
        return $this->website;
    }
}
