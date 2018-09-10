<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasType;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasStatus;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use DateTime;


/**
 * @ORM\Entity
 * @ORM\Table(name="acctg_cdj_transaction")
 */

class CDJTransaction
{
    use HasGeneratedID;
    use HasCode;
    use TrackCreate;

    /** @ORM\OneToMany(targetEntity="CDJJournalEntry", mappedBy="transaction") */
    protected $entries;


    public function __construct()
    {
    	$this->date_create = new DateTime();
    }

    public function setCDJCode($record_date)
    {
    	// $date = new DateTime();
    	$str_date = $record_date->format('dmY');
    	$this->code = $str_date."-".str_pad($this->getID(), 8, "0",STR_PAD_LEFT);
    }
}