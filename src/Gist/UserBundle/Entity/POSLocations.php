<?php

namespace Gist\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_pos_locations")
 */

class POSLocations
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $name;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $leasor;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $contact_number;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $coordinates;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $locator_desc;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $brand;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $city;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $postal;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $region;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $country;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Areas")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;




    public function __construct()
    {
        $this->initTrackCreate();
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
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }


    /**
     * Set leasor
     *
     * @param string $leasor
     *
     * @return POSLocations
     */
    public function setLeasor($leasor)
    {
        $this->leasor = $leasor;

        return $this;
    }

    /**
     * Get leasor
     *
     * @return string
     */
    public function getLeasor()
    {
        return $this->leasor;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return POSLocations
     */
    public function setContactNumber($contact_number)
    {
        $this->contact_number = $contact_number;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contact_number;
    }

    /**
     * Set coordinates
     *
     * @param string $coordinates
     *
     * @return POSLocations
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    /**
     * Get coordinates
     *
     * @return string
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set locatorDesc
     *
     * @param string $locatorDesc
     *
     * @return POSLocations
     */
    public function setLocatorDesc($locatorDesc)
    {
        $this->locator_desc = $locatorDesc;

        return $this;
    }

    /**
     * Get locatorDesc
     *
     * @return string
     */
    public function getLocatorDesc()
    {
        return $this->locator_desc;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return POSLocations
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
     * Set brand
     *
     * @param string $brand
     *
     * @return POSLocations
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return POSLocations
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
     * Set postal
     *
     * @param string $postal
     *
     * @return POSLocations
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set region
     *
     * @param string $region
     *
     * @return POSLocations
     */
    public function setRegion($region)
    {
        $this->region = $region;

        return $this;
    }

    /**
     * Get region
     *
     * @return string
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Set country
     *
     * @param string $country
     *
     * @return POSLocations
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
     * Set status
     *
     * @param string $status
     *
     * @return POSLocations
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
     * Set area
     *
     * @param \Gist\UserBundle\Entity\Areas $area
     *
     * @return POSLocations
     */
    public function setArea(\Gist\UserBundle\Entity\Areas $area = null)
    {
        $this->area = $area;

        return $this;
    }

    /**
     * Get area
     *
     * @return \Gist\UserBundle\Entity\Areas
     */
    public function getArea()
    {
        return $this->area;
    }
}
