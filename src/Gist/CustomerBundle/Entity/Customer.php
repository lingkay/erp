<?php

namespace Gist\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_customer_info")
 */

class Customer
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $first_name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $last_name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $c_email_address;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $mobile_number;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $status;


    public function __construct()
    {
        $this->initTrackCreate();
    }

    
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Customer
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
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Customer
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
     * Set cEmailAddress
     *
     * @param string $cEmailAddress
     *
     * @return Customer
     */
    public function setCEmailAddress($cEmailAddress)
    {
        $this->c_email_address = $cEmailAddress;

        return $this;
    }

    /**
     * Get cEmailAddress
     *
     * @return string
     */
    public function getCEmailAddress()
    {
        return $this->c_email_address;
    }

    /**
     * Set mobileNumber
     *
     * @param string $mobileNumber
     *
     * @return Customer
     */
    public function setMobileNumber($mobileNumber)
    {
        $this->mobile_number = $mobileNumber;

        return $this;
    }

    /**
     * Get mobileNumber
     *
     * @return string
     */
    public function getMobileNumber()
    {
        return $this->mobile_number;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Customer
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
}
