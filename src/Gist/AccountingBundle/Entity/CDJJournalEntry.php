<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\JournalEntryAbstract;

class CDJJournalEntry extends JournalEntryAbstract
{
    
    public function toData()
    {
        $data = parent::toData();

        return $data;
    }
}
