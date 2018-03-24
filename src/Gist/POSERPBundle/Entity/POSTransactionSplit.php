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
 * @ORM\Table(name="gist_pos_trans_split")
 */

class POSTransactionSplit
{


    use HasGeneratedID;
    use TrackCreate;


    /**
     * @ORM\ManyToOne(targetEntity="Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="consultant_id", referencedColumnName="id")
     */
    protected $consultant;


    /**
     * @ORM\ManyToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

    /** @ORM\Column(type="string", length=20) */
    protected $amount;

    /** @ORM\Column(type="string", length=20) */
    protected $percent;

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
     * Set amount
     *
     * @param string $amount
     *
     * @return POSTransactionSplit
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set percent
     *
     * @param string $percent
     *
     * @return POSTransactionSplit
     */
    public function setPercent($percent)
    {
        $this->percent = $percent;

        return $this;
    }

    /**
     * Get percent
     *
     * @return string
     */
    public function getPercent()
    {
        return $this->percent;
    }

    /**
     * Set consultant
     *
     * @param \Gist\UserBundle\Entity\User $consultant
     *
     * @return POSTransactionSplit
     */
    public function setConsultant($consultant)
    {
        $this->consultant = $consultant;

        return $this;
    }

    /**
     * Get consultant
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getConsultant()
    {
        return $this->consultant;
    }

    /**
     * Set transaction
     *
     * @param \Gist\POSBundle\Entity\POSTransaction $transaction
     *
     * @return POSTransactionSplit
     */
    public function setTransaction($transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Gist\POSBundle\Entity\POSTransaction
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
