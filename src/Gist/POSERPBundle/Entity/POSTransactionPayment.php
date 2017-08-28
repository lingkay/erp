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
 * @ORM\Table(name="gist_pos_trans_payment")
 */

class POSTransactionPayment
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $details;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $amount;

    /**
     * @ORM\ManyToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

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
     * Set type
     *
     * @param string $type
     *
     * @return POSTransactionPayments
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
     * Set details
     *
     * @param string $details
     *
     * @return POSTransactionPayments
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return POSTransactionPayments
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
     * Set transaction
     *
     * @param \Gist\POSBundle\Entity\POSTransactions $transaction
     *
     * @return POSTransactionPayments
     */
    public function setTransaction($transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Gist\POSBundle\Entity\POSTransactions
     */
    public function getTransaction()
    {
        return $this->transaction;
    }
}
