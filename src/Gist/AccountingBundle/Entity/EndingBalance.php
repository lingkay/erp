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
 * @ORM\Table(name="acct_ending_balance")
 */

class EndingBalance
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\AccountingBundle\Entity\MonthEndClosing")
     * @ORM\JoinColumn(name="month_end_id", referencedColumnName="id", nullable=true)
     */
    protected $month_end_closing;

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

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     */
    protected $beginning;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     */
    protected $ending;


    /** @ORM\Column(type="string", length=20) */
    protected $month;

    /** @ORM\Column(type="string", length=4) */
    protected $year;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->credit = 0.0;
        $this->debit = 0.0;
        $this->beginning = 0.0;
        $this->ending = 0.0;
    }

    public function setMonthEnd($month_end)
    {
        $this->month_end_closing = $month_end;
        return $this;
    }

    public function getMonthEnd()
    {
        return $this->month_end_closing;
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

    public function setBeginning($beginning)
    {
        $this->beginning = $beginning;
        return $this;
    }

    public function getBeginning()
    {
        return $this->beginning;
    }

    public function setEnding($ending)
    {
        $this->ending = $ending;
        return $this;
    }

    public function getEnding()
    {
        return $this->ending;
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
