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
 * @ORM\Table(name="acct_balance")
 */

class TrialBalance
{

    use HasGeneratedID;
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



    /** @ORM\Column(type="string", length=20) */
    protected $month;

    /** @ORM\Column(type="string", length=4) */
    protected $year;

    public function __construct($account)
    {
        $this->initTrackCreate();
        $this->credit = 0.0;
        $this->debit = 0.0;
        $this->status = self::STATUS_DRAFT;
        $this->chart_of_account = $account;
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

    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    public function getMonth()
    {
        return $this->month;
    }

    public function setYear($year)
    {
        $this->year = $year;
        return $this;
    }

    public function getYear()
    {
        return $this->year;
    }
}
