<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="payroll_settings_incentive_matrix")
 */
class IncentiveMatrix
{
    use HasGeneratedID;

    /** @ORM\Column(type="string", nullable=true)*/
    protected $bracket;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_from;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $percent_amount;

    /**
     * @ORM\ManyToOne(targetEntity="Incentive")
     * @ORM\JoinColumn(name="incentive_id", referencedColumnName="id")
     */
    protected $incentive;


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
     * @return IncentiveMatrix
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
     * @return IncentiveMatrix
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
     * @return IncentiveMatrix
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
     * @return IncentiveMatrix
     */
    public function setPercentAmount($percentAmount)
    {
        $this->percent_amount = $percentAmount;

        return $this;
    }

    /**
     * Get percentAmount
     *
     * @return string
     */
    public function getPercentAmount()
    {
        return $this->percent_amount;
    }

    /**
     * Set incentive
     *
     * @param \Hris\AdminBundle\Entity\Incentive $incentive
     *
     * @return IncentiveMatrix
     */
    public function setIncentive(\Hris\AdminBundle\Entity\Incentive $incentive = null)
    {
        $this->incentive = $incentive;

        return $this;
    }

    /**
     * Get incentive
     *
     * @return \Hris\AdminBundle\Entity\Incentive
     */
    public function getIncentive()
    {
        return $this->incentive;
    }
}
