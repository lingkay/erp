<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_bank_charge")
 */

class BankCharge
{


    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="string", length=150, nullable=false) */
    protected $rate_name;

    /** @ORM\Column(type="string", length=150, nullable=false) */
    protected $rate_value;

    /**
     * @ORM\ManyToOne(targetEntity="BankAccount")
     * @ORM\JoinColumn(name="bank_id", referencedColumnName="id")
     */
    protected $bank;




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
     * Set rateName
     *
     * @param string $rateName
     *
     * @return BankCharge
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
     * @return BankCharge
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

    /**
     * Set bank
     *
     * @param \Gist\AccountingBundle\Entity\Bank $bank
     *
     * @return BankCharge
     */
    public function setBank(\Gist\AccountingBundle\Entity\BankAccount $bank = null)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return \Gist\AccountingBundle\Entity\Bank
     */
    public function getBank()
    {
        return $this->bank;
    }
}
