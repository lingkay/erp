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



    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $first_name;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $middle_name;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $last_name;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $c_email_address;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $mobile_number;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $status;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $gender;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $marital_status;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $date_married;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $home_phone;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $birthdate;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $address1;

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $address2;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $city;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $state;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $country;

    /** @ORM\Column(type="string", length=25, nullable=true) */
    protected $zip;

    /** @ORM\Column(type="string", length=25, nullable=true) */
    protected $notes;


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

    public function getNameFormatted()
    {
        return $this->last_name . ', '.$this->first_name;
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

    /**
     * Set middleName
     *
     * @param string $middleName
     *
     * @return Customer
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
     * Set gender
     *
     * @param string $gender
     *
     * @return Customer
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set maritalStatus
     *
     * @param string $maritalStatus
     *
     * @return Customer
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->marital_status = $maritalStatus;

        return $this;
    }

    /**
     * Get maritalStatus
     *
     * @return string
     */
    public function getMaritalStatus()
    {
        return $this->marital_status;
    }

    /**
     * Set dateMarried
     *
     * @param string $dateMarried
     *
     * @return Customer
     */
    public function setDateMarried($dateMarried)
    {
        $this->date_married = $dateMarried;

        return $this;
    }

    /**
     * Get dateMarried
     *
     * @return string
     */
    public function getDateMarried()
    {
        return $this->date_married;
    }

    /**
     * Set homePhone
     *
     * @param string $homePhone
     *
     * @return Customer
     */
    public function setHomePhone($homePhone)
    {
        $this->home_phone = $homePhone;

        return $this;
    }

    /**
     * Get homePhone
     *
     * @return string
     */
    public function getHomePhone()
    {
        return $this->home_phone;
    }

    /**
     * Set birthdate
     *
     * @param string $birthdate
     *
     * @return Customer
     */
    public function setBirthdate($birthdate)
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    /**
     * Get birthdate
     *
     * @return string
     */
    public function getBirthdate()
    {
        return $this->birthdate;
    }

    /**
     * Set address1
     *
     * @param string $address1
     *
     * @return Customer
     */
    public function setAddress1($address1)
    {
        $this->address1 = $address1;

        return $this;
    }

    /**
     * Get address1
     *
     * @return string
     */
    public function getAddress1()
    {
        return $this->address1;
    }

    /**
     * Set address2
     *
     * @param string $address2
     *
     * @return Customer
     */
    public function setAddress2($address2)
    {
        $this->address2 = $address2;

        return $this;
    }

    /**
     * Get address2
     *
     * @return string
     */
    public function getAddress2()
    {
        return $this->address2;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Customer
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Customer
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return Customer
     */
    public function setCountry($country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set zip
     *
     * @param string $zip
     *
     * @return Customer
     */
    public function setZip($zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set notes
     *
     * @param string $notes
     *
     * @return Customer
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;

        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }
}
