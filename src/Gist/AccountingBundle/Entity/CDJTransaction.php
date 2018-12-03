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
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="acctg_cdj_transaction")
 */

class CDJTransaction
{
    use HasGeneratedID;
    use HasCode;
    use HasNotes;
    use TrackCreate;
    use HasStatus;

    const STATUS_DRAFT = "Draft";
    const STATUS_SAVED = "Saved";

    /** @ORM\OneToMany(targetEntity="CDJJournalEntry", mappedBy="transaction") */
    protected $entries;

    /** @ORM\OneToMany(targetEntity="CDJTempEntry", mappedBy="transaction") */
    protected $temp_entries;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $record_date;


    /** 
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="certified_id", referencedColumnName="id")
     */
    protected $certified_by;

    /** 
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="approved_by", referencedColumnName="id")
     */
    protected $approved_by;


    /** @ORM\Column(type="string", nullable=true) */
    protected $payee;
    
    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $payee_text;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $payment_type;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $check_number;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $bank;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $bank_text;

    public function __construct()
    {
    	$this->date_create = new DateTime();
        $this->status = self::STATUS_DRAFT;
        $this->bank_text = "";
        $this->payee_text = "";
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function getTempEntries()
    {
        return $this->temp_entries;
    }

    public function setCDJCode($record_date)
    {
    	// $date = new DateTime();
    	$str_date = $record_date->format('dmY');
    	$this->code = $str_date."-".str_pad($this->getID(), 8, "0",STR_PAD_LEFT);
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

    public function setCertifiedBy($certified_by)
    {
        $this->certified_by = $certified_by;
        return $this;
    }

    public function getCertifiedBy()
    {
        return $this->certified_by;
    }

    public function setApprovedBy($approved_by)
    {
        $this->approved_by = $approved_by;
        return $this;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
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

    public function setBank($bank)
    {
        $this->bank = $bank;
        return $this;
    }

    public function getBank()
    {
        return $this->bank;
    }

    public function setBankText($bank_text)
    {
        $this->bank_text = $bank_text;
        return $this;
    }

    public function getBankText()
    {
        return $this->bank_text;
    }


    public function setPaymentType($payment_type)
    {
        $this->payment_type = $payment_type;
        return $this;
    }

    public function getPaymentType()
    {
        return $this->payment_type;
    }

    public function setPayee($payee)
    {
        $this->payee = $payee;
        return $this;
    }

    public function getPayee()
    {
        return $this->payee;
    }

    public function setPayeeText($payee_text)
    {
        $this->payee_text = $payee_text;
        return $this;
    }

    public function getPayeeText()
    {
        return $this->payee_text;
    }


    public function toData()
    {

        $data = new stdClass();
        $data->id = $this->id;
        $this->dataHasStatus($data);
        return $data;
    }

}