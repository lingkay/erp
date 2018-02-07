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
 * @ORM\Table(name="inv_counting")
 */
class Counting
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\Column(type="string")
     */
    protected $remarks;

    /**
     * @ORM\Column(type="string")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Account")
     * @ORM\JoinColumn(name="inv_account_id", referencedColumnName="id")
     */
    protected $inventory_account;

    /**
     * @ORM\OneToMany(targetEntity="CountingEntry", mappedBy="stock_transfer", cascade={"persist"})
     */
    protected $entries;

    /** @ORM\Column(type="date") */
    protected $date_submitted;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->entries = new ArrayCollection();
    }

    public function setDateSubmitted($date_submitted)
    {
        $this->date_submitted = $date_submitted;
        return $this;
    }

    public function getDateSubmitted()
    {
        return $this->date_submitted;
    }

    public function setRemarks($desc)
    {
        $this->remarks = $desc;
        return $this;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function setInventoryAccount($inventory_account)
    {
        $this->inventory_account = $inventory_account;
        return $this;
    }

    public function getInventoryAccount()
    {
        return $this->inventory_account;
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

    public function getStatusFMTD()
    {
        return ucfirst($this->status);
    }

    public function addEntry(Entry $entry)
    {
        $entry->setTransaction($this);
        $this->entries->add($entry);
        return $this;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function toData()
    {
        $entries = array();

        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $data->remarks = $this->remarks;

        foreach ($this->getEntries() as $entry)
            $entries[] = $entry->toData();
        $data->entries = $entries;

        return $data;
    }
}

