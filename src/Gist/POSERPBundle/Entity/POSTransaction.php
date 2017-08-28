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
 * @ORM\Table(name="gist_pos_trans")
 */

class POSTransaction
{


    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $trans_display_id;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_total;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_balance;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $customer_id;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="cust_link_id", referencedColumnName="id")
     */
    protected $customer;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $status;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $synced_to_erp;

    /** @ORM\OneToMany(targetEntity="POSTransactionItem", mappedBy="transaction") */
    protected $items;

    /** @ORM\OneToMany(targetEntity="POSTransactionPayment", mappedBy="transaction") */
    protected $payments;




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

    public function getItems()
    {
        return $this->items;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getTotalPayments()
    {
        $total = 0;

        foreach ($this->payments as $p) {
            $total = $total + $p->getAmount();
        }

        return $total;
    }

    /**
     * Set transactionTotal
     *
     * @param string $transactionTotal
     *
     * @return POSTransactions
     */
    public function setTransactionTotal($transactionTotal)
    {
        $this->transaction_total = $transactionTotal;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get transactionTotal
     *
     * @return string
     */
    public function getTransactionTotal()
    {
        return $this->transaction_total;
    }

    /**
     * Set trnasactionBalance
     *
     * @param string $trnasactionBalance
     *
     * @return POSTransactions
     */
    public function setTrnasactionBalance($trnasactionBalance)
    {
        $this->trnasaction_balance = $trnasactionBalance;

        return $this;
    }

    /**
     * Get trnasactionBalance
     *
     * @return string
     */
    public function getTrnasactionBalance()
    {
        return $this->trnasaction_balance;
    }

    /**
     * Set customerId
     *
     * @param string $customerId
     *
     * @return POSTransactions
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return POSTransactions
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
     * Set transId
     *
     * @param string $transId
     *
     * @return POSTransaction
     */
    public function setTransId($transId)
    {
        $this->trans_id = $transId;

        return $this;
    }

    /**
     * Get transId
     *
     * @return string
     */
    public function getTransId()
    {
        return $this->trans_id;
    }

    /**
     * Set transDisplayId
     *
     * @param string $transDisplayId
     *
     * @return POSTransaction
     */
    public function setTransDisplayId($transDisplayId)
    {
        $this->trans_display_id = $transDisplayId;

        return $this;
    }

    /**
     * Get transDisplayId
     *
     * @return string
     */
    public function getTransDisplayId()
    {
        return $this->trans_display_id;
    }

    /**
     * Set transactionType
     *
     * @param string $transactionType
     *
     * @return POSTransaction
     */
    public function setTransactionType($transactionType)
    {
        $this->transaction_type = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    public function getTransactionTypeFormatted()
    {
        $ret_val = 'n/a';
        if ($this->transaction_type == 'reg') {
            $ret_val = 'Regular';
        } elseif ($this->transaction_type == 'bulk') {
            $ret_val = 'Bulk Discount';
        } elseif ($this->transaction_type == 'per') {
            $ret_val = 'Per-item Discount';
        } else {
            $ret_val = 'N/A';
        }

        return $ret_val;
    }

    /**
     * Set syncedToErp
     *
     * @param string $syncedToErp
     *
     * @return POSTransaction
     */
    public function setSyncedToErp($syncedToErp)
    {
        $this->synced_to_erp = $syncedToErp;

        return $this;
    }

    /**
     * Get syncedToErp
     *
     * @return string
     */
    public function getSyncedToErp()
    {
        return $this->synced_to_erp;
    }

    /**
     * Set transactionBalance
     *
     * @param string $transactionBalance
     *
     * @return POSTransaction
     */
    public function setTransactionBalance($transactionBalance)
    {
        $this->transaction_balance = $transactionBalance;

        return $this;
    }

    /**
     * Get transactionBalance
     *
     * @return string
     */
    public function getTransactionBalance()
    {
        return $this->transaction_balance;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    public function getCustomer()
    {
        return $this->customer;
    }


}
