<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\AccountingBundle\Entity\JournalEntryAbstract;

/**
 * @ORM\Entity
 */
class CDJJournalEntry extends JournalEntryAbstract
{

    /**
     * @ORM\ManyToOne(targetEntity="CDJTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;
    
    public function setTransaction(CDJTransaction $transaction)
    {
    	$this->transaction = $transaction;
    	return $this;
    }

    public function getTransaction()
    {
    	return $this->transaction;
    }

    public function toData()
    {
        $data = parent::toData();

        return $data;
    }
}
