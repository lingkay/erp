<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_terminal")
 */

class Terminal
{


    use HasGeneratedID;
    use TrackCreate;




    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\POSLocations")
     * @ORM\JoinColumn(name="actual_location_id", referencedColumnName="id")
     */
    protected $actual_location;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\POSLocations")
     * @ORM\JoinColumn(name="registered_location_id", referencedColumnName="id")
     */
    protected $registered_location;

    

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $terminal_of;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $bank;

    /**
     * @ORM\ManyToOne(targetEntity="BankAccount")
     * @ORM\JoinColumn(name="bank_account_id", referencedColumnName="id")
     */
    protected $bank_account;

    /**
     * @ORM\ManyToOne(targetEntity="TerminalOperator")
     * @ORM\JoinColumn(name="terminal_operator_id", referencedColumnName="id")
     */
    protected $terminal_operator;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $payment_type;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $mid;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $tid;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $serial_number;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $sim_card_number;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $brand;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $model;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $status;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $remarks;


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
     * Set actualLocation
     *
     * @param string $actualLocation
     *
     * @return Terminal
     */
    public function setActualLocation($actualLocation)
    {
        $this->actual_location = $actualLocation;

        return $this;
    }

    /**
     * Get actualLocation
     *
     * @return string
     */
    public function getActualLocation()
    {
        return $this->actual_location;
    }

    /**
     * Set registeredLocation
     *
     * @param string $registeredLocation
     *
     * @return Terminal
     */
    public function setRegisteredLocation($registeredLocation)
    {
        $this->registered_location = $registeredLocation;

        return $this;
    }

    /**
     * Get registeredLocation
     *
     * @return string
     */
    public function getRegisteredLocation()
    {
        return $this->registered_location;
    }

    

    /**
     * Set terminalOf
     *
     * @param string $terminalOf
     *
     * @return Terminal
     */
    public function setTerminalOf($terminalOf)
    {
        $this->terminal_of = $terminalOf;

        return $this;
    }

    /**
     * Get terminalOf
     *
     * @return string
     */
    public function getTerminalOf()
    {
        return $this->terminal_of;
    }

    /**
     * Set bank
     *
     * @param string $bank
     *
     * @return Terminal
     */
    public function setBank($bank)
    {
        $this->bank = $bank;

        return $this;
    }

    /**
     * Get bank
     *
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * Set paymentType
     *
     * @param string $paymentType
     *
     * @return Terminal
     */
    public function setPaymentType($paymentType)
    {
        $this->payment_type = $paymentType;

        return $this;
    }

    /**
     * Get paymentType
     *
     * @return string
     */
    public function getPaymentType()
    {
        return $this->payment_type;
    }

    /**
     * Set mid
     *
     * @param string $mid
     *
     * @return Terminal
     */
    public function setMID($mid)
    {
        $this->mid = $mid;

        return $this;
    }

    /**
     * Get mid
     *
     * @return string
     */
    public function getMID()
    {
        return $this->mid;
    }

    /**
     * Set tid
     *
     * @param string $tid
     *
     * @return Terminal
     */
    public function setTID($tid)
    {
        $this->tid = $tid;

        return $this;
    }

    /**
     * Get tid
     *
     * @return string
     */
    public function getTID()
    {
        return $this->tid;
    }

    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     *
     * @return Terminal
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serial_number = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serial_number;
    }

    /**
     * Set simCardNumber
     *
     * @param string $simCardNumber
     *
     * @return Terminal
     */
    public function setSimCardNumber($simCardNumber)
    {
        $this->sim_card_number = $simCardNumber;

        return $this;
    }

    /**
     * Get simCardNumber
     *
     * @return string
     */
    public function getSimCardNumber()
    {
        return $this->sim_card_number;
    }

    /**
     * Set brand
     *
     * @param string $brand
     *
     * @return Terminal
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set model
     *
     * @param string $model
     *
     * @return Terminal
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get model
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Terminal
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set remarks
     *
     * @param string $remarks
     *
     * @return Terminal
     */
    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    /**
     * Get remarks
     *
     * @return string
     */
    public function getRemarks()
    {
        return $this->remarks;
    }

    /**
     * Set bankAccount
     *
     * @param \Gist\AccountingBundle\Entity\BankAccount $bankAccount
     *
     * @return Terminal
     */
    public function setBankAccount(\Gist\AccountingBundle\Entity\BankAccount $bankAccount = null)
    {
        $this->bank_account = $bankAccount;

        return $this;
    }

    /**
     * Get bankAccount
     *
     * @return \Gist\AccountingBundle\Entity\BankAccount
     */
    public function getBankAccount()
    {
        return $this->bank_account;
    }


    public function setTerminalOperator(\Gist\AccountingBundle\Entity\TerminalOperator $terminal_operator = null)
    {
        $this->terminal_operator = $terminal_operator;

        return $this;
    }

    public function getTerminalOperator()
    {
        return $this->terminal_operator;
    }


}
