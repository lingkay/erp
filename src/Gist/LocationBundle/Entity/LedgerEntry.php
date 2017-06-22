<?php

namespace Gist\LocationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="ledger_entry")
 */

class LedgerEntry
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=50) */
    protected $amount;

    /** @ORM\Column(type="date") */
    protected $entry_date;




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
     * @return LedgerEntry
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
     * Set entryDate
     *
     * @param \DateTime $entryDate
     *
     * @return LedgerEntry
     */
    public function setEntryDate($entryDate)
    {
        $this->entry_date = $entryDate;

        return $this;
    }

    /**
     * Get entryDate
     *
     * @return \DateTime
     */
    public function getEntryDate()
    {
        return $this->entry_date;
    }
}
