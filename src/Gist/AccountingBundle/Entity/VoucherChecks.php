<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="acctg_cdj_voucher_check")
 */

class VoucherChecks
{

    use HasGeneratedID;
  

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\AccountingBundle\Entity\CDJTransaction")
     * @ORM\JoinColumn(name="voucher_id", referencedColumnName="id", nullable=true)
     */
    protected $voucher;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    protected $check_number;

    /**
     * @ORM\Column(type="decimal", precision=15, scale=3)
     */
    protected $amount;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $record_date;

    public function __construct($number,DateTime $date, $amount)
    {
        $this->record_date = new DateTime();
        $this->check_number = $number;
        $this->record_date = $date;
        $this->amount = $amount;
    }


    public function setCheckDate(DateTime $record)
    {
        $this->record_date = $record;
        return $this;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCheckDate()
    {
        return $this->record_date;
    }

    public function setCheckNumber($check_number)
    {
        $this->check_number = $check_number;
        return $this;
    }

    public function getCheckNumber()
    {
        return $this->check_number;
    }

    public function setVoucher(\Gist\AccountingBundle\Entity\CDJTransaction $voucher)
    {
        $this->voucher = $voucher;
        return $this;
    }

    public function getVoucher()
    {
        return $this->voucher;
    }
}
