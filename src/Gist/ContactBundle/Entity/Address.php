<?php

namespace Gist\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

/**
 * @ORM\Entity
 * @ORM\Table(name="cnt_address")
 */
class Address
{
    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="string", length=50) */
    protected $name;

    /** @ORM\Column(type="string", length=150) */
    protected $street;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\WorldLocation")
     * @ORM\JoinColumn(name="city", referencedColumnName="id")
    */
    protected $city;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\WorldLocation")
     * @ORM\JoinColumn(name="state", referencedColumnName="id")
    */
    protected $state;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\WorldLocation")
     * @ORM\JoinColumn(name="country", referencedColumnName="id")
    */
    protected $country;

    /** @ORM\Column(type="decimal", precision=10, scale=7) */
    protected $latitude;

    /** @ORM\Column(type="decimal", precision=10, scale=7) */
    protected $longitude;

    /** @ORM\Column(type="boolean") */
    protected $is_primary;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->is_primary = false;
        $this->latitude = 28.6667;
        $this->longitude = 77.2167;
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

    public function setStreet($street)
    {
        $this->street = $street;
        return $this;
    }

    public function getStreet()
    {
        return $this->street;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getCityName()
    {
        return $this->getCity()->getName();
    }

    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    public function getState()
    {
        return $this->state;
    }

    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    public function getCountry()
    {
        return $this->country;
    }

    public function setLatitude($lat)
    {
        $this->latitude = $lat;
        return $this;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function setLongitude($long)
    {
        $this->longitude = $long;
        return $this;
    }

    public function getLongitude()
    {
        return $this->longitude;
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

    public function getFullAddress()
    {
        return $this->name.', '.$this->street.', '.$this->city->getName().', '.$this->state->getName().', '.$this->country->getName();
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        $data->name = $this->name;
        $data->street = $this->street;
        $data->city = $this->city->toData();
        $data->state = $this->state->toData();
        $data->country = $this->country->toData();
        $data->latitude = $this->latitude;
        $data->longitude = $this->longitude;
        $data->is_primary = $this->is_primary;

        return $data;
    }
}
