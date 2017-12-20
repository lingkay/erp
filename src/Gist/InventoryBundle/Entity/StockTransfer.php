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
}
