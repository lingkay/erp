<?php

namespace Gist\LocationBundle\Entity;

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
 * @ORM\Table(name="loc_areas")
 */

class Areas
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=50) */
    protected $name;

    /**
     * @ORM\ManyToOne(targetEntity="Regions")
     * @ORM\JoinColumn(name="region_id", referencedColumnName="id")
     */
    protected $region;

    /**
     * @ORM\OneToMany(targetEntity="\Gist\LocationBundle\Entity\POSLocations", mappedBy="area", cascade={"persist", "remove"})
     */
    protected $locations;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function getLocations()
    {
        return $this->locations;
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
        $data->name = $this->name;
        return $data;
    }

}
