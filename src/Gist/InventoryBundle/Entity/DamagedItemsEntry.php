<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\TrackCreate;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_damaged_items_entry")
 */
class DamagedItemsEntry
{
    use HasGeneratedID;
    use HasProduct;
    use TrackCreate;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="DamagedItems")
     * @ORM\JoinColumn(name="damaged_items_id", referencedColumnName="id")
     */
    protected $damaged_items;

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
     * @ORM\JoinColumn(name="receiving_user", referencedColumnName="id")
     */
    protected $receiving_user;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_received;

    /**
     * @ORM\Column(type="string")
     */
    protected $remarks;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;


    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->debit = 0;
        $this->credit = 0;
        $this->initTrackCreate();
    }

    public function getSource()
    {
        return $this->source_inv_account;
    }

    public function setDestination($inv_acct)
    {
        $this->destination_inv_account = $inv_acct;
        return $this;
    }

    public function setSource($inv_acct)
    {
        $this->source_inv_account = $inv_acct;
        return $this;
    }

    public function getDestination()
    {
        return $this->destination_inv_account;
    }

    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    public function setDamagedItems(DamagedItems $trans)
    {
        $this->damaged_items = $trans;
        return $this;
    }

    public function getQuantity()
    {
        return number_format($this->quantity);
    }

    public function getDamagedItems()
    {
        return $this->damaged_items;
    }

    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
        return $this;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function getStatusFMTD()
    {
        return ucfirst($this->status);
    }

    /**
     * Set dateProcessed
     *
     * @param \DateTime $dateProcessed
     *
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return DamagedItemsEntry
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
     * @return \stdClass
     */
    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasProduct($data);
        $data->stock_transfer_id = $this->getStockTransfer()->getID();

        return $data;
    }
}

