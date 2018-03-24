<?php

namespace Gist\POSERPBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="gist_pos_trans_payment")
 */

class POSTransactionPayment
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $details;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $amount;

    // GENERIC
    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $bank;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $trans_date;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $control_number;

    // CARD
    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_name;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_expiry;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_cvv;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_terms;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_class;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $interest;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $card_terminal_operator;

    // CHECK
    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $account_number;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $payee;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $payor;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $check_type;

    /**
     * @ORM\ManyToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="payment_issued_on", referencedColumnName="id")
     */
    protected $payment_issued_on;

    /**
     * @ORM\ManyToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $check_date;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    /**
     * Set type
     *
     * @param $check_type
     * @return $this
     * @internal param string $type
     */
    public function setCheckDate($check_date)
    {
        $this->check_date = $check_date;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getCheckDate()
    {
        return $this->check_date;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

    /**
     * Set type
     *
     * @param $check_type
     * @return $this
     * @internal param string $type
     */
    public function setCheckType($check_type)
    {
        $this->check_type = $check_type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getCheckType()
    {
        return $this->check_type;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return POSTransactionPayments
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set details
     *
     * @param string $details
     *
     * @return POSTransactionPayments
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return POSTransactionPayments
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
     * Set transaction
     *
     * @param \Gist\POSBundle\Entity\POSTransactions $transaction
     *
     * @return POSTransactionPayments
     */
    public function setTransaction($transaction = null)
    {
        $this->transaction = $transaction;

        return $this;
    }

    /**
     * Get transaction
     *
     * @return \Gist\POSBundle\Entity\POSTransactions
     */
    public function getTransaction()
    {
        return $this->transaction;
    }

    /**
     * Set bank
     *
     * @param string $bank
     *
     * @return POSTransactionPayment
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
        if ($this->bank == null || $this->bank == 'null') {
            return '';
        }

        return $this->bank;
    }

    /**
     * Set transDate
     *
     * @param string $transDate
     *
     * @return POSTransactionPayment
     */
    public function setTransDate($transDate)
    {
        $this->trans_date = $transDate;

        return $this;
    }

    /**
     * Get transDate
     *
     * @return string
     */
    public function getTransDate()
    {
        return $this->trans_date;
    }

    /**
     * Set number
     *
     * @param string $number
     *
     * @return POSTransactionPayment
     */
    public function setControlNumber($control_number)
    {
        $this->control_number = $control_number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getControlNumber()
    {
        return $this->control_number;
    }

    /**
     * Set cardType
     *
     * @param string $cardType
     *
     * @return POSTransactionPayment
     */
    public function setCardType($cardType)
    {
        $this->card_type = $cardType;

        return $this;
    }

    /**
     * Get cardType
     *
     * @return string
     */
    public function getCardType()
    {
        return $this->card_type;
    }

    /**
     * Set cardName
     *
     * @param string $cardName
     *
     * @return POSTransactionPayment
     */
    public function setCardName($cardName)
    {
        $this->card_name = $cardName;

        return $this;
    }

    /**
     * Get cardName
     *
     * @return string
     */
    public function getCardName()
    {
        return $this->card_name;
    }

    /**
     * Set cardExpiry
     *
     * @param string $cardExpiry
     *
     * @return POSTransactionPayment
     */
    public function setCardExpiry($cardExpiry)
    {
        $this->card_expiry = $cardExpiry;

        return $this;
    }

    /**
     * Get cardExpiry
     *
     * @return string
     */
    public function getCardExpiry()
    {
        return $this->card_expiry;
    }

    /**
     * Set cardCvv
     *
     * @param string $cardCvv
     *
     * @return POSTransactionPayment
     */
    public function setCardCvv($cardCvv)
    {
        $this->card_cvv = $cardCvv;

        return $this;
    }

    /**
     * Get cardCvv
     *
     * @return string
     */
    public function getCardCvv()
    {
        return $this->card_cvv;
    }

    /**
     * Set cardTerms
     *
     * @param string $cardTerms
     *
     * @return POSTransactionPayment
     */
    public function setCardTerms($cardTerms)
    {
        $this->card_terms = $cardTerms;

        return $this;
    }

    /**
     * Get cardTerms
     *
     * @return string
     */
    public function getCardTerms()
    {
        if ($this->card_terms == 'null') {
            return '';
        }
        return $this->card_terms;
    }

    /**
     * Set cardClass
     *
     * @param string $cardClass
     *
     * @return POSTransactionPayment
     */
    public function setCardClass($cardClass)
    {
        $this->card_class = $cardClass;

        return $this;
    }

    /**
     * Get cardClass
     *
     * @return string
     */
    public function getCardClass()
    {
        return $this->card_class;
    }

    /**
     * Set cardTerminalOperator
     *
     * @param string $cardTerminalOperator
     *
     * @return POSTransactionPayment
     */
    public function setCardTerminalOperator($cardTerminalOperator)
    {
        $this->card_terminal_operator = $cardTerminalOperator;

        return $this;
    }

    /**
     * Get cardTerminalOperator
     *
     * @return string
     */
    public function getCardTerminalOperator()
    {
        if ($this->card_terminal_operator == 'null') {
            return '';
        }
        return $this->card_terminal_operator;
    }

    /**
     * Set checkAccountNumber
     *
     * @param string $checkAccountNumber
     *
     * @return POSTransactionPayment
     */
    public function setCheckAccountNumber($checkAccountNumber)
    {
        $this->check_account_number = $checkAccountNumber;

        return $this;
    }

    /**
     * Get checkAccountNumber
     *
     * @return string
     */
    public function getCheckAccountNumber()
    {
        return $this->check_account_number;
    }

    /**
     * Set checkPayee
     *
     * @param string $checkPayee
     *
     * @return POSTransactionPayment
     */
    public function setCheckPayee($checkPayee)
    {
        $this->check_payee = $checkPayee;

        return $this;
    }

    /**
     * Get checkPayee
     *
     * @return string
     */
    public function getCheckPayee()
    {
        return $this->check_payee;
    }

    /**
     * Set checkPayor
     *
     * @param string $checkPayor
     *
     * @return POSTransactionPayment
     */
    public function setCheckPayor($checkPayor)
    {
        $this->check_payor = $checkPayor;

        return $this;
    }

    /**
     * Get checkPayor
     *
     * @return string
     */
    public function getCheckPayor()
    {
        return $this->check_payor;
    }

    /**
     * Set interest
     *
     * @param string $interest
     *
     * @return POSTransactionPayment
     */
    public function setInterest($interest)
    {
        $this->interest = $interest;

        return $this;
    }

    /**
     * Get interest
     *
     * @return string
     */
    public function getInterest()
    {
        if ($this->interest == 'null') {
            return '';
        }
        return $this->interest;
    }

    /**
     * Set accountNumber
     *
     * @param string $accountNumber
     *
     * @return POSTransactionPayment
     */
    public function setAccountNumber($accountNumber)
    {
        $this->account_number = $accountNumber;

        return $this;
    }

    /**
     * Get accountNumber
     *
     * @return string
     */
    public function getAccountNumber()
    {
        return $this->account_number;
    }

    /**
     * Set payee
     *
     * @param string $payee
     *
     * @return POSTransactionPayment
     */
    public function setPayee($payee)
    {
        $this->payee = $payee;

        return $this;
    }

    /**
     * Get payee
     *
     * @return string
     */
    public function getPayee()
    {
        return $this->payee;
    }

    /**
     * Set payor
     *
     * @param string $payor
     *
     * @return POSTransactionPayment
     */
    public function setPayor($payor)
    {
        $this->payor = $payor;

        return $this;
    }

    /**
     * Get payor
     *
     * @return string
     */
    public function getPayor()
    {
        return $this->payor;
    }

    /**
     * Set paymentIssuedOn
     *
     * @param string $paymentIssuedOn
     *
     * @return POSTransactionPayment
     */
    public function setPaymentIssuedOn($paymentIssuedOn)
    {
        $this->payment_issued_on = $paymentIssuedOn;

        return $this;
    }

    /**
     * Get paymentIssuedOn
     *
     * @return string
     */
    public function getPaymentIssuedOn()
    {
        return $this->payment_issued_on;
    }
}
