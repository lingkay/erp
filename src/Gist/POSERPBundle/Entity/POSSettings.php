<?php

namespace Gist\POSERPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="gist_poserp_settings")
 */

class POSSettings
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $name;


    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $value;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return POSSettings
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



    /**
     * Set value
     *
     * @param string $value
     *
     * @return POSSettings
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    public function getValueFormatted()
    {
        if ($this->value == 'incl') {
            return 'Inclusive';
        } elseif ($this->value == 'excl') {
            return 'Exclusive';
        }
        
        return $this->value;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }
}
