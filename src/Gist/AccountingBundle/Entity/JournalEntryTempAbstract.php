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
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="acctg_journal_temp")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="journal_type", type="string")
 * @ORM\DiscriminatorMap({"cdj_entries" = "\Gist\AccountingBundle\Entity\CDJTempEntry",
 *						
 *                         })
 */

abstract class JournalEntryTempAbstract
{

    use HasGeneratedID;
    use HasType;
    use HasCode;
    use HasStatus; 
    use TrackCreate;
    use HasNotes;

    const STATUS_DRAFT = "Draft";
    const STATUS_APPROVED = "Approved";

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\AccountingBundle\Entity\ChartOfAccount")
     * @ORM\JoinColumn(name="account_id", referencedColumnName="id", nullable=true)
     */
    protected $chart_of_account;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     */
    protected $credit;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     */
    protected $debit;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $record_date;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->initHasType();
        $this->initHasCode();
        $this->credit = 0.0;
        $this->debit = 0.0;
        $this->status = self::STATUS_DRAFT;
    }

    public function setAccount($account)
    {
        $this->chart_of_account = $account;
        return $this;
    }

    public function getAccount()
    {
        return $this->chart_of_account;
    }

    public function setRecordDate(DateTime $record)
    {
        $this->record_date = $record;
        return $this;
    }

    public function getRecordDate()
    {
        return $this->record_date;
    }

    public function setDebit($debit)
    {
        $this->debit = $debit;
        return $this;
    }

    public function getDebit()
    {
        return $this->debit;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }

    public function getCredit()
    {
        return $this->credit;
    }
}
