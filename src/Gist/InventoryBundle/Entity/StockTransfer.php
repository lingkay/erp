<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\UserBundle\Entity\User;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_stock_transfer")
 */
class StockTransfer
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Account")
     * @ORM\JoinColumn(name="destination_inv_account_id", referencedColumnName="id")
     */
    protected $destination_inv_account;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Account")
     * @ORM\JoinColumn(name="source_inv_account_id", referencedColumnName="id")
     */
    protected $source_inv_account;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="requesting_user", referencedColumnName="id")
     */
    protected $requesting_user;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="processed_user", referencedColumnName="id")
     */
    protected $processed_user;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_processed;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="deliver_user", referencedColumnName="id")
     */
    protected $deliver_user;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_delivered;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="receiving_user", referencedColumnName="id")
     */
    protected $receiving_user;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_received;


    /**
     * @ORM\OneToMany(targetEntity="StockTransferEntry", mappedBy="stock_transfer", cascade={"persist"})
     */
    protected $entries;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->entries = new ArrayCollection();
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
        return $this;
    }

    public function setSource($inv_acct)
    {
        $this->source_inv_account = $inv_acct;
        return $this;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function setDestination($inv_acct)
    {
        $this->destination_inv_account = $inv_acct;
        return $this;
    }


    public function addEntry(Entry $entry)
    {
        $entry->setTransaction($this);
        $this->entries->add($entry);
        return $this;
    }


    public function getDescription()
    {
        return $this->description;
    }

    public function getSource()
    {
        return $this->source_inv_account;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getDestination()
    {
        return $this->destination_inv_account;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function checkBalance()
    {
        // check entries if all credits = debits per product
        $index_check = array();

        // go through entries
        foreach ($this->entries as $entry)
        {
            $id = $entry->getProduct()->getID();

            // build the index checker
            if (!isset($index_check[$id]))
                $index_check[$id] = array('debit' => '0.00', 'credit' => '0.00');

            $index_check[$id]['debit'] = bcadd($index_check[$id]['debit'], $entry->getDebit(), 2);
            $index_check[$id]['credit'] = bcadd($index_check[$id]['credit'], $entry->getCredit(), 2);
        }

        foreach ($index_check as $ic)
        {
            if ($ic['debit'] != $ic['credit'])
                return false;
        }

        return true;
    }

    public function toData()
    {
        $entries = array();

        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $data->description = $this->description;

        foreach ($this->getEntries() as $entry)
            $entries[] = $entry->toData();
        $data->entries = $entries;

        return $data;
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     *
     * @return StockTransfer
     */
    public function setDateProcessed($dateProcessed)
    {
        $this->date_processed = $dateProcessed;

        return $this;
    }

    /**
     * Get dateProcessed
     *
     * @return \DateTime
     */
    public function getDateProcessed()
    {
        return $this->date_processed;
    }

    /**
     * Set dateDelivered
     *
     * @param \DateTime $dateDelivered
     *
     * @return StockTransfer
     */
    public function setDateDelivered($dateDelivered)
    {
        $this->date_delivered = $dateDelivered;

        return $this;
    }

    /**
     * Get dateDelivered
     *
     * @return \DateTime
     */
    public function getDateDelivered()
    {
        return $this->date_delivered;
    }

    /**
     * Set dateReceived
     *
     * @param \DateTime $dateReceived
     *
     * @return StockTransfer
     */
    public function setDateReceived($dateReceived)
    {
        $this->date_received = $dateReceived;

        return $this;
    }

    /**
     * Get dateReceived
     *
     * @return \DateTime
     */
    public function getDateReceived()
    {
        return $this->date_received;
    }

    /**
     * Set destinationInvAccount
     *
     * @param \Gist\InventoryBundle\Entity\Account $destinationInvAccount
     *
     * @return StockTransfer
     */
    public function setDestinationInvAccount(\Gist\InventoryBundle\Entity\Account $destinationInvAccount = null)
    {
        $this->destination_inv_account = $destinationInvAccount;

        return $this;
    }

    /**
     * Get destinationInvAccount
     *
     * @return \Gist\InventoryBundle\Entity\Account
     */
    public function getDestinationInvAccount()
    {
        return $this->destination_inv_account;
    }

    /**
     * Set sourceInvAccount
     *
     * @param \Gist\InventoryBundle\Entity\Account $sourceInvAccount
     *
     * @return StockTransfer
     */
    public function setSourceInvAccount(\Gist\InventoryBundle\Entity\Account $sourceInvAccount = null)
    {
        $this->source_inv_account = $sourceInvAccount;

        return $this;
    }

    /**
     * Get sourceInvAccount
     *
     * @return \Gist\InventoryBundle\Entity\Account
     */
    public function getSourceInvAccount()
    {
        return $this->source_inv_account;
    }

    /**
     * Set requestingUser
     *
     * @param \Gist\UserBundle\Entity\User $requestingUser
     *
     * @return StockTransfer
     */
    public function setRequestingUser(\Gist\UserBundle\Entity\User $requestingUser = null)
    {
        $this->requesting_user = $requestingUser;

        return $this;
    }

    /**
     * Get requestingUser
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getRequestingUser()
    {
        return $this->requesting_user;
    }

    /**
     * Set processedUser
     *
     * @param \Gist\UserBundle\Entity\User $processedUser
     *
     * @return StockTransfer
     */
    public function setProcessedUser(\Gist\UserBundle\Entity\User $processedUser = null)
    {
        $this->processed_user = $processedUser;

        return $this;
    }

    /**
     * Get processedUser
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getProcessedUser()
    {
        return $this->processed_user;
    }

    /**
     * Set deliverUser
     *
     * @param \Gist\UserBundle\Entity\User $deliverUser
     *
     * @return StockTransfer
     */
    public function setDeliverUser(\Gist\UserBundle\Entity\User $deliverUser = null)
    {
        $this->deliver_user = $deliverUser;

        return $this;
    }

    /**
     * Get deliverUser
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getDeliverUser()
    {
        return $this->deliver_user;
    }

    /**
     * Set receivingUser
     *
     * @param \Gist\UserBundle\Entity\User $receivingUser
     *
     * @return StockTransfer
     */
    public function setReceivingUser(\Gist\UserBundle\Entity\User $receivingUser = null)
    {
        $this->receiving_user = $receivingUser;

        return $this;
    }

    /**
     * Get receivingUser
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getReceivingUser()
    {
        return $this->receiving_user;
    }

    /**
     * Remove entry
     *
     * @param \Gist\InventoryBundle\Entity\StockTransferEntry $entry
     */
    public function removeEntry(\Gist\InventoryBundle\Entity\StockTransferEntry $entry)
    {
        $this->entries->removeElement($entry);
    }
}
