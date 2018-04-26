<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="payroll_settings_tax_matrix_table")
 */
class TaxMatrixTable
{
    use HasGeneratedID;

    /** @ORM\Column(type="string", nullable=true)*/
    protected $bracket;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_from;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $tax_amount;

    /**
     * @ORM\ManyToOne(targetEntity="TaxMatrix")
     * @ORM\JoinColumn(name="tax_matrix_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $tax;


    public function __construct()
    {

    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }

    /**
     * Set bracket
     *
     * @param string $bracket
     *
     * @return TaxMatrixTable
     */
    public function setBracket($bracket)
    {
        $this->bracket = $bracket;

        return $this;
    }

    /**
     * Get bracket
     *
     * @return string
     */
    public function getBracket()
    {
        return $this->bracket;
    }

    /**
     * Set amountFrom
     *
     * @param string $amountFrom
     *
     * @return TaxMatrixTable
     */
    public function setAmountFrom($amountFrom)
    {
        $this->amount_from = $amountFrom;

        return $this;
    }

    /**
     * Get amountFrom
     *
     * @return string
     */
    public function getAmountFrom()
    {
        return $this->amount_from;
    }

    /**
     * Set amountTo
     *
     * @param string $amountTo
     *
     * @return TaxMatrixTable
     */
    public function setAmountTo($amountTo)
    {
        $this->amount_to = $amountTo;

        return $this;
    }

    /**
     * Get amountTo
     *
     * @return string
     */
    public function getAmountTo()
    {
        return $this->amount_to;
    }

    /**
     * Set percentAmount
     *
     * @param string $percentAmount
     *
     * @return TaxMatrixTable
     */
    public function setTaxAmount($percentAmount)
    {
        $this->tax_amount = $percentAmount;

        return $this;
    }

    /**
     * Get percentAmount
     *
     * @return string
     */
    public function getTaxAmount()
    {
        return $this->tax_amount;
    }

    /**
     * Set incentive
     *
     * @param TaxMatrix $incentive
     *
     * @return TaxMatrixTable
     */
    public function setTaxMatrix(\Hris\AdminBundle\Entity\TaxMatrix $incentive = null)
    {
        $this->tax = $incentive;

        return $this;
    }

    /**
     * Get incentive
     *
     * @return \Hris\AdminBundle\Entity\TaxMatrix
     */
    public function getTaxMatrix()
    {
        return $this->tax;
    }
}
