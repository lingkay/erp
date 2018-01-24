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
 * @ORM\Table(name="inv_damaged_items")
 */
class DamagedItems
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;

    /**
     * @ORM\OneToMany(targetEntity="DamagedItemsEntry", mappedBy="damaged_items", cascade={"persist"})
     */
    protected $entries;

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

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->entries = new ArrayCollection();
    }

    public function getStatus()
    {
        $status = 'For return';
        foreach ($this->entries as $e) {
            if ($e->getStatus() == 'returned') {
                $status = 'Returned';
            }
        }

        return $status;
    }

    public function setDescription($desc)
    {
        $this->description = $desc;
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

    public function getEntries()
    {
        return $this->entries;
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

    /**
     * Remove entry
     *
     * @param \Gist\InventoryBundle\Entity\StockTransferEntry $entry
     */
    public function removeEntry(\Gist\InventoryBundle\Entity\StockTransferEntry $entry)
    {
        $this->entries->removeElement($entry);
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
}
