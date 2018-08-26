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
 * @ORM\Table(name="gist_pos_trans")
 */

class POSTransaction
{
    use HasGeneratedID;
    use TrackCreate;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $trans_display_id;

    /** @ORM\Column(type="float", nullable=true) */
    protected $transaction_total;

    /** @ORM\Column(type="float", nullable=true) */
    protected $transaction_balance;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $customer_id;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\CustomerBundle\Entity\Customer")
     * @ORM\JoinColumn(name="cust_link_id", referencedColumnName="id")
     */
    protected $customer;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_type;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_mode;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $transaction_cc_interest;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $status;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $tax_rate;

    /** @ORM\Column(type="float", nullable=true) */
    protected $orig_vat_amt;

    /** @ORM\Column(type="float", nullable=true) */
    protected $new_vat_amt;

    /** @ORM\Column(type="float", nullable=true) */
    protected $orig_amt_net_vat;

    /** @ORM\Column(type="float", nullable=true) */
    protected $new_amt_net_vat;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $tax_coverage;

    /** @ORM\Column(type="float", nullable=true) */
    protected $cart_min;

    /** @ORM\Column(type="float", nullable=true) */
    protected $cart_orig_total;

    /** @ORM\Column(type="float", nullable=true) */
    protected $cart_new_total;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $bulk_discount_type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $selected_bulk_discount_type;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $selected_bulk_discount_amount;

    /** @ORM\OneToMany(targetEntity="POSTransactionItem", mappedBy="transaction") */
    protected $items;

    /** @ORM\OneToMany(targetEntity="POSTransactionPayment", mappedBy="transaction") */
    protected $payments;

    /** @ORM\OneToMany(targetEntity="POSTransactionSplit", mappedBy="transaction") */
    protected $splits;

    /** @ORM\Column(type="float", nullable=true) */
    protected $deposit_vat_amt;

    /** @ORM\Column(type="float", nullable=true) */
    protected $deposit_amt_net_vat;

    /** @ORM\Column(type="float", nullable=true) */
    protected $balance_vat_amt;

    /** @ORM\Column(type="float", nullable=true) */
    protected $balance_amt_net_vat;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $balance;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $deposit_amount;

    /** @ORM\Column(type="float", nullable=true) */
    protected $gc_credit_amount;

    /** @ORM\Column(type="float", nullable=true) */
    protected $gc_debit_amount;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\POSLocations")
     * @ORM\JoinColumn(name="pos_location_id", referencedColumnName="id")
     */
    protected $pos_location;


    /**
     * @ORM\OneToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", nullable=true)
     */
    private $reference_transaction;

    /**
     * @ORM\OneToOne(targetEntity="POSTransaction", mappedBy="reference_transaction")
     */
    private $child_transaction;

    /** @ORM\Column(type="float", nullable=true) */
    protected $extra_amount;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $date_modified;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $location;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $refundMethod;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $refundAmount;

    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $generic_var1;

    /** @ORM\Column(type="string", length=255, nullable=true) */
    protected $remarks;

    /** @ORM\Column(type="float", nullable=true) */
    protected $total_discount;


    /**
     * POSTransaction constructor.
     */
    public function __construct()
    {
        $this->initTrackCreate();
    }

    /**
     * @return stdClass
     */
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

    /**
     * @param $pos_location
     * @return $this
     */
    public function setPOSLocation($pos_location)
    {
        $this->pos_location = $pos_location;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPOSLocation()
    {
        return $this->pos_location;
    }

    /**
     * @return mixed
     */
    public function getDiscountAmount()
    {
        return ($this->transaction_total - $this->cart_orig_total);
    }

    /**
     * @return string
     */
    public function getPercentOfSale()
    {
        return round((($this->transaction_total/$this->cart_orig_total)*100),2)."%";
    }

    /**
     * @return mixed
     */
    public function getCartTotalFormatted()
    {
        if ($this->cart_new_total != 0) {
            return $this->cart_new_total;
        } else {
            return $this->cart_orig_total;
        }
    }

    /**
     * @return bool
     */
    public function hasItems()
    {
        if (count($this->items) > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return bool
     */
    public function hasChildLayeredReport()
    {
        if ($this->child_transaction == null) {
            return false;
        } else {
            if ($this->child_transaction->transaction_mode == 'upsell') {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * @return string
     */
    public function getTransDisplayIdFormatted()
    {
        $ret = $this->trans_display_id;

        if ($this->transaction_mode == 'quotation') {
            $ret = 'Quotation';
        }
        return $ret;
    }

    /**
     * @return bool
     */
    public function hasPayments()
    {
        if (count($this->payments) > 0) {
            return true;
        }

        return false;
    }

    public function getFinalVAT()
    {
        if (($this->transaction_type == 'per' || $this->transaction_type == 'bulk')) {
            return $this->new_vat_amt;
        }

        return $this->orig_vat_amt;
    }

    public function getRefundVAT()
    {
        if (($this->reference_transaction->transaction_type == 'per' || $this->reference_transaction->transaction_type == 'bulk')) {
            return $this->new_vat_amt;
        }

        return $this->orig_vat_amt;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function getPayments()
    {
        return $this->payments;
    }

    public function getSplits()
    {
        return $this->splits;
    }

    public function getTotalPayments()
    {
        $total = 0;

        if ($this->hasParent() && !$this->hasPayments()) {
            foreach ($this->reference_transaction->getPayments() as $p) {
                $total = $total + $p->getAmount();
            }

            return $total;
        }

        foreach ($this->payments as $p) {
            $total = $total + $p->getAmount();
        }

        return $total;
    }

    /**
     * @return int
     */
    public function getPaymentIssued()
    {
        $total = 0;
        // if ($this->hasParent() && !$this->hasPayments()) {
        //     foreach ($this->reference_transaction->getPayments() as $p) {
        //         if ($this->id == $p->getPaymentIssuedOn()->getID()) {
        //             $total = $total + $p->getAmount();
        //         }
        //     }

        //     return $total;
        // }

        foreach ($this->payments as $p) {
            if ($this->id == $p->getPaymentIssuedOn()->getID()) {
                $total = $total + $p->getAmount();
            }
        }

        return $total;
    }

    public function getTransactionTotalER()
    {
        $total = 0;

        foreach ($this->items as $p) {
            if ($p->getReturned() == false) {
                if ($this->transaction_type == 'per') {
                    $total = $total + $p->getAdjustedPrice();
                } else {
                    $total = $total + $p->getOrigPrice();
                }
            }
        }

        return $total;
    }

    public function getRefundTotalER()
    {
        $total = 0;

        if ($this->refundAmount == 0) {
            $total = 0;
        } else {
            if ($this->reference_transaction->transaction_type != 'bulk') {
                foreach ($this->items as $p) {
                    if ($p->getReturned() == true) {
                        if ($this->transaction_type == 'per') {
                            $total = $total + $p->getAdjustedPrice();
                        } else {
                            $total = $total + $p->getOrigPrice();
                        }
                    }
                }
            } else {
                $total = $this->refundAmount;
            }
        }


        return $total;
    }


    public function getAmountIssued()
    {
        $total = 0;

        if ($this->hasItems()) {
            foreach ($this->getItems() as $p) {
                if ($p->getItemIssuedOn()) {
                    if ($this->id == $p->getItemIssuedOn()->getID()) {
                        if ($this->transaction_type == 'per') {
                            $total = $total + $p->getAdjustedPrice();
                        } else {
                            $total = $total + $p->getOrigPrice();
                        }
                    }
                }
            }
        }

        return $total;
    }

    public function getChange()
    {
        if ($this->transaction_mode == 'exchange' && $this->refundAmount == 0) {
            return 0;
        }

        $change = $this->getTotalPayments() - $this->transaction_total;
        if ($change > 0) {
            return $change;
        }

        return 0;

    }

    public function setERPID($erp_id)
    {
        $this->erp_id = $erp_id;

        return $this;
    }

    public function getERPID()
    {
        return $this->erp_id;
    }


    public function setTransactionMode($transaction_mode)
    {
        $this->transaction_mode = $transaction_mode;

        return $this;
    }

    public function getTransactionMode()
    {
        return $this->transaction_mode;
    }

    public function getTransactionModeFormatted()
    {
        return ucfirst($this->transaction_mode);
    }

    public function setTransactionCCInterest($transaction_cc_interest)
    {
        $this->transaction_cc_interest = $transaction_cc_interest;

        return $this;
    }

    public function getRemarks()
    {
        return ucfirst($this->remarks);
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;

        return $this;
    }

    public function getTransactionCCInterest()
    {
        return $this->transaction_cc_interest;
    }

    /**
     * Set transactionTotal
     *
     * @param string $transactionTotal
     *
     * @return POSTransactions
     */
    public function setTransactionTotal($transactionTotal)
    {
        $this->transaction_total = $transactionTotal;

        return $this;
    }

    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get transactionTotal
     *
     * @return string
     */
    public function getTransactionTotal()
    {
        return $this->transaction_total;
    }

    /**
     * Set trnasactionBalance
     *
     * @param string $trnasactionBalance
     *
     * @return POSTransactions
     */
    public function setTrnasactionBalance($trnasactionBalance)
    {
        $this->trnasaction_balance = $trnasactionBalance;

        return $this;
    }

    /**
     * Get trnasactionBalance
     *
     * @return string
     */
    public function getTrnasactionBalance()
    {
        return $this->trnasaction_balance;
    }

    /**
     * Set customerId
     *
     * @param string $customerId
     *
     * @return POSTransactions
     */
    public function setCustomerId($customerId)
    {
        $this->customer_id = $customerId;

        return $this;
    }

    public function setCustomer($customer)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customerId
     *
     * @return string
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return POSTransactions
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
     * Set transId
     *
     * @param string $transId
     *
     * @return POSTransaction
     */
    public function setTransId($transId)
    {
        $this->trans_id = $transId;

        return $this;
    }

    /**
     * Get transId
     *
     * @return string
     */
    public function getTransId()
    {
        return $this->trans_id;
    }

    /**
     * Set transDisplayId
     *
     * @param string $transDisplayId
     *
     * @return POSTransaction
     */
    public function setTransDisplayId($transDisplayId)
    {
        $this->trans_display_id = $transDisplayId;

        return $this;
    }

    /**
     * Get transDisplayId
     *
     * @return string
     */
    public function getTransDisplayId()
    {
        return $this->trans_display_id;
    }

    /**
     * Set transactionType
     *
     * @param string $transactionType
     *
     * @return POSTransaction
     */
    public function setTransactionType($transactionType)
    {
        $this->transaction_type = $transactionType;

        return $this;
    }

    /**
     * Get transactionType
     *
     * @return string
     */
    public function getTransactionType()
    {
        return $this->transaction_type;
    }

    /**
     * Set RefundMethod
     *
     * @param string $refundMethod
     *
     * @return POSTransaction
     */
    public function setRefundMethod($refundMethod)
    {
        $this->refundMethod = $refundMethod;

        return $this;
    }

    /**
     * Get RefundMethod
     *
     * @return string
     */
    public function getRefundMethod()
    {
        return $this->refundMethod;
    }

    /**
     * Set RefundAmount
     *
     * @param string $refundAmount
     *
     * @return POSTransaction
     */
    public function setRefundAmount($refundAmount)
    {
        $this->refundAmount = $refundAmount;

        return $this;
    }

    /**
     * Get RefundAmount
     *
     * @return string
     */
    public function getRefundAmount()
    {
        return $this->refundAmount;
    }

    public function getTransactionTypeFormatted()
    {
        $ret_val = 'n/a';
        if ($this->transaction_type == 'reg') {
            $ret_val = 'Regular';
        } elseif ($this->transaction_type == 'bulk') {
            $ret_val = 'Bulk Discount';
        } elseif ($this->transaction_type == 'per') {
            $ret_val = 'Per-item Discount';
        } else {
            $ret_val = 'N/A';
        }

        return $ret_val;
    }

    /**
     * Set syncedToErp
     *
     * @param string $syncedToErp
     *
     * @return POSTransaction
     */
    public function setSyncedToErp($syncedToErp)
    {
        $this->synced_to_erp = $syncedToErp;

        return $this;
    }

    /**
     * Get syncedToErp
     *
     * @return string
     */
    public function getSyncedToErp()
    {
        return $this->synced_to_erp;
    }

    /**
     * Set transactionBalance
     *
     * @param string $transactionBalance
     *
     * @return POSTransaction
     */
    public function setTransactionBalance($transactionBalance)
    {
        $this->transaction_balance = $transactionBalance;

        return $this;
    }

    /**
     * Get transactionBalance
     *
     * @return string
     */
    public function getTransactionBalance()
    {
        return $this->transaction_balance;
    }

    /**
     * Set taxRate
     *
     * @param string $taxRate
     *
     * @return POSTransaction
     */
    public function setTaxRate($taxRate)
    {
        $this->tax_rate = $taxRate;

        return $this;
    }

    /**
     * Get taxRate
     *
     * @return string
     */
    public function getTaxRate()
    {
        return $this->tax_rate;
    }

    /**
     * Set origVatAmt
     *
     * @param string $origVatAmt
     *
     * @return POSTransaction
     */
    public function setOrigVatAmt($origVatAmt)
    {
        $this->orig_vat_amt = $origVatAmt;

        return $this;
    }

    /**
     * Get origVatAmt
     *
     * @return string
     */
    public function getOrigVatAmt()
    {
        return $this->orig_vat_amt;
    }

    /**
     * Set newVatAmt
     *
     * @param string $newVatAmt
     *
     * @return POSTransaction
     */
    public function setNewVatAmt($newVatAmt)
    {
        $this->new_vat_amt = $newVatAmt;

        return $this;
    }

    /**
     * Get newVatAmt
     *
     * @return string
     */
    public function getNewVatAmt()
    {
        return $this->new_vat_amt;
    }

    /**
     * Set origAmtNetVat
     *
     * @param string $origAmtNetVat
     *
     * @return POSTransaction
     */
    public function setOrigAmtNetVat($origAmtNetVat)
    {
        $this->orig_amt_net_vat = $origAmtNetVat;

        return $this;
    }

    /**
     * Get origAmtNetVat
     *
     * @return string
     */
    public function getOrigAmtNetVat()
    {
        return $this->orig_amt_net_vat;
    }

    /**
     * Set newAmtNetVat
     *
     * @param string $newAmtNetVat
     *
     * @return POSTransaction
     */
    public function setNewAmtNetVat($newAmtNetVat)
    {
        $this->new_amt_net_vat = $newAmtNetVat;

        return $this;
    }

    /**
     * Get newAmtNetVat
     *
     * @return string
     */
    public function getNewAmtNetVat()
    {
        return $this->new_amt_net_vat;
    }

    /**
     * Set taxCoverage
     *
     * @param string $taxCoverage
     *
     * @return POSTransaction
     */
    public function setTaxCoverage($taxCoverage)
    {
        $this->tax_coverage = $taxCoverage;

        return $this;
    }

    /**
     * Get taxCoverage
     *
     * @return string
     */
    public function getTaxCoverage()
    {
        return $this->tax_coverage;
    }

    /**
     * Set cartMin
     *
     * @param string $cartMin
     *
     * @return POSTransaction
     */
    public function setCartMin($cartMin)
    {
        $this->cart_min = $cartMin;

        return $this;
    }

    /**
     * Get cartMin
     *
     * @return string
     */
    public function getCartMin()
    {
        return $this->cart_min;
    }

    /**
     * Set cartOrigTotal
     *
     * @param string $cartOrigTotal
     *
     * @return POSTransaction
     */
    public function setCartOrigTotal($cartOrigTotal)
    {
        $this->cart_orig_total = $cartOrigTotal;

        return $this;
    }

    /**
     * Get cartOrigTotal
     *
     * @return string
     */
    public function getCartOrigTotal()
    {
        return $this->cart_orig_total;
    }

    /**
     * Set cartNewTotal
     *
     * @param string $cartNewTotal
     *
     * @return POSTransaction
     */
    public function setCartNewTotal($cartNewTotal)
    {
        $this->cart_new_total = $cartNewTotal;

        return $this;
    }

    /**
     * Get cartNewTotal
     *
     * @return string
     */
    public function getCartNewTotal()
    {
        return $this->cart_new_total;
    }

    /**
     * Set bulkDiscountType
     *
     * @param string $bulkDiscountType
     *
     * @return POSTransaction
     */
    public function setBulkDiscountType($bulkDiscountType)
    {
        $this->bulk_discount_type = $bulkDiscountType;

        return $this;
    }

    /**
     * Get bulkDiscountType
     *
     * @return string
     */
    public function getBulkDiscountType()
    {
        return $this->bulk_discount_type;
    }

    /**
     * Add item
     *
     * @param \Gist\POSBundle\Entity\POSTransactionItem $item
     *
     * @return POSTransaction
     */
    public function addItem($item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \Gist\POSBundle\Entity\POSTransactionItem $item
     */
    public function removeItem($item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Add payment
     *
     * @param \Gist\POSBundle\Entity\POSTransactionPayment $payment
     *
     * @return POSTransaction
     */
    public function addPayment($payment)
    {
        $this->payments[] = $payment;

        return $this;
    }

    /**
     * Remove payment
     *
     * @param \Gist\POSBundle\Entity\POSTransactionPayment $payment
     */
    public function removePayment($payment)
    {
        $this->payments->removeElement($payment);
    }

    /**
     * Set referenceTransaction
     *
     * @param \Gist\POSBundle\Entity\POSTransaction $referenceTransaction
     *
     * @return POSTransaction
     */
    public function setReferenceTransaction($referenceTransaction = null)
    {
        $this->reference_transaction = $referenceTransaction;

        return $this;
    }

    /**
     * Get referenceTransaction
     *
     * @return \Gist\POSBundle\Entity\POSTransaction
     */
    public function getReferenceTransaction()
    {
        return $this->reference_transaction;
    }

    public function getReferenceTransactionDisplayID()
    {
        if ($this->reference_transaction != null) {
            return $this->reference_transaction->getTransDisplayId();
        }

        return '-';

    }

    public function getChildType()
    {
        if ($this->child_transaction == null) {
            return 'null';
        }
        $child = $this->child_transaction;
        return $child->getTransactionMode();
    }

    public function hasChild()
    {
        if ($this->child_transaction == null) {
            return false;
        }

        return true;
    }

    public function hasSplit()
    {
        if (count($this->splits) > 0) {
            return true;
        }

        return false;
    }

    public function hasParent()
    {
        if ($this->reference_transaction == null) {
            return false;
        }

        return true;
    }

    /**
     * Set extraAmount
     *
     * @param string $extraAmount
     *
     * @return POSTransaction
     */
    public function setExtraAmount($extraAmount)
    {
        $this->extra_amount = $extraAmount;

        return $this;
    }

    /**
     * Get extraAmount
     *
     * @return string
     */
    public function getExtraAmount()
    {
        return $this->extra_amount;
    }

    /**
     * Set dateModified
     *
     * @param string $dateModified
     *
     * @return POSTransaction
     */
    public function setDateModified($dateModified)
    {
        $this->date_modified = $dateModified;

        return $this;
    }

    /**
     * Get dateModified
     *
     * @return string
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }

    /**
     * Set location
     *
     * @param string $location
     *
     * @return POSTransaction
     */
    public function setLocation($location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set genericVar1
     *
     * @param string $genericVar1
     *
     * @return POSTransaction
     */
    public function setGenericVar1($genericVar1)
    {
        $this->generic_var1 = $genericVar1;

        return $this;
    }

    /**
     * Get genericVar1
     *
     * @return string
     */
    public function getGenericVar1()
    {
        return $this->generic_var1;
    }

    /**
     * Set depositVatAmt
     *
     * @param string $depositVatAmt
     *
     * @return POSTransaction
     */
    public function setDepositVatAmt($depositVatAmt)
    {
        $this->deposit_vat_amt = $depositVatAmt;

        return $this;
    }

    /**
     * Get depositVatAmt
     *
     * @return string
     */
    public function getDepositVatAmt()
    {
        return $this->deposit_vat_amt;
    }

    /**
     * Set depositAmtNetVat
     *
     * @param string $depositAmtNetVat
     *
     * @return POSTransaction
     */
    public function setDepositAmtNetVat($depositAmtNetVat)
    {
        $this->deposit_amt_net_vat = $depositAmtNetVat;

        return $this;
    }

    /**
     * Get depositAmtNetVat
     *
     * @return string
     */
    public function getDepositAmtNetVat()
    {
        return $this->deposit_amt_net_vat;
    }

    /**
     * Set balanceVatAmt
     *
     * @param string $balanceVatAmt
     *
     * @return POSTransaction
     */
    public function setBalanceVatAmt($balanceVatAmt)
    {
        $this->balance_vat_amt = $balanceVatAmt;

        return $this;
    }

    /**
     * Get balanceVatAmt
     *
     * @return string
     */
    public function getBalanceVatAmt()
    {
        return $this->balance_vat_amt;
    }

    /**
     * Set balanceAmtNetVat
     *
     * @param string $balanceAmtNetVat
     *
     * @return POSTransaction
     */
    public function setBalanceAmtNetVat($balanceAmtNetVat)
    {
        $this->balance_amt_net_vat = $balanceAmtNetVat;

        return $this;
    }

    /**
     * Get balanceAmtNetVat
     *
     * @return string
     */
    public function getBalanceAmtNetVat()
    {
        return $this->balance_amt_net_vat;
    }

    /**
     * Set balance
     *
     * @param string $balance
     *
     * @return POSTransaction
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get balance
     *
     * @return string
     */
    public function getBalance()
    {
        return $this->balance;
    }

    public function getBalanceDisplay()
    {
        if ($this->balance < 0) {
            return 0;
        }
        return $this->balance;
    }

    /**
     * Set depositAmount
     *
     * @param string $depositAmount
     *
     * @return POSTransaction
     */
    public function setDepositAmount($depositAmount)
    {
        $this->deposit_amount = $depositAmount;

        return $this;
    }

    /**
     * Get depositAmount
     *
     * @return string
     */
    public function getDepositAmount()
    {
        return $this->deposit_amount;
    }

    /**
     * Add split
     *
     * @param \Gist\POSBundle\Entity\POSTransactionSplit $split
     *
     * @return POSTransaction
     */
    public function addSplit(POSTransactionSplit $split)
    {
        $this->splits[] = $split;

        return $this;
    }

    /**
     * Remove split
     *
     * @param \Gist\POSBundle\Entity\POSTransactionSplit $split
     */
    public function removeSplit(POSTransactionSplit $split)
    {
        $this->splits->removeElement($split);
    }

    /**
     * Set childTransaction
     *
     * @param \Gist\POSBundle\Entity\POSTransaction $childTransaction
     *
     * @return POSTransaction
     */
    public function setChildTransaction(POSTransaction $childTransaction = null)
    {
        $this->child_transaction = $childTransaction;

        return $this;
    }

    /**
     * Get childTransaction
     *
     * @return \Gist\POSBundle\Entity\POSTransaction
     */
    public function getChildTransaction()
    {
        return $this->child_transaction;
    }

    /**
     * Set selectedBulkDiscountType
     *
     * @param string $selectedBulkDiscountType
     *
     * @return POSTransaction
     */
    public function setSelectedBulkDiscountType($selectedBulkDiscountType)
    {
        $this->selected_bulk_discount_type = $selectedBulkDiscountType;

        return $this;
    }

    /**
     * Get selectedBulkDiscountType
     *
     * @return string
     */
    public function getSelectedBulkDiscountType()
    {
        return $this->selected_bulk_discount_type;
    }

    /**
     * Set selectedBulkDiscountAmount
     *
     * @param string $selectedBulkDiscountAmount
     *
     * @return POSTransaction
     */
    public function setSelectedBulkDiscountAmount($selectedBulkDiscountAmount)
    {
        $this->selected_bulk_discount_amount = $selectedBulkDiscountAmount;

        return $this;
    }

    /**
     * Get selectedBulkDiscountAmount
     *
     * @return string
     */
    public function getSelectedBulkDiscountAmount()
    {
        return $this->selected_bulk_discount_amount;
    }

    /**
     * Set setGCCredit
     *
     * @param string $gc_credit_amount
     *
     * @return POSTransaction
     */
    public function setGCCredit($gc_credit_amount)
    {
        $this->gc_credit_amount = $gc_credit_amount;

        return $this;
    }

    /**
     * Get getGCCredit
     *
     * @return string
     */
    public function getGCCredit()
    {
        return $this->gc_credit_amount;
    }

    /**
     * Get getGCDebit
     *
     * @return string
     */
    public function getGCDebit()
    {
        return $this->gc_debit_amount;
    }

    /**
     * Set setGCDebit
     *
     * @param string $gc_debit_amount
     *
     * @return POSTransaction
     */
    public function setGCDebit($gc_debit_amount)
    {
        $this->gc_debit_amount = $gc_debit_amount;

        return $this;
    }


    /**
     * Get getGCCredit fmtd
     *
     * @return string
     */
    public function getGCCreditAbsolute()
    {
        return abs($this->gc_credit_amount);
    }

    public function getTotalDiscount()
    {
        return $this->total_discount;
    }

    public function setTotalDiscount($total_discount)
    {
        $this->total_discount = $total_discount;

        return $this;
    }
}
