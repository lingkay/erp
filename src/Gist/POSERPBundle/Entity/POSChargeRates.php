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
 * @ORM\Table(name="gist_poserp_charge_rates")
 */

class POSChargeRates
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $rate_name;


    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $rate_value;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    /**
     * Set rateName
     *
     * @param string $rateName
     *
     * @return ChargeRates
     */
    public function setRateName($rateName)
    {
        $this->rate_name = $rateName;

        return $this;
    }

    /**
     * Get rateName
     *
     * @return string
     */
    public function getRateName()
    {
        return $this->rate_name;
    }

    /**
     * Set rateValue
     *
     * @param string $rateValue
     *
     * @return ChargeRates
     */
    public function setRateValue($rateValue)
    {
        $this->rate_value = $rateValue;

        return $this;
    }

    /**
     * Get rateValue
     *
     * @return string
     */
    public function getRateValue()
    {
        return $this->rate_value;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }
    
}
