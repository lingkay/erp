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
 * @ORM\Table(name="inv_transaction")
 */
class Transaction
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\Column(type="string")
     */
    protected $description;


    /**
     * @ORM\OneToMany(targetEntity="Entry", mappedBy="transaction", cascade={"persist"})
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
