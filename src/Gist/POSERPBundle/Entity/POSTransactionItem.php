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
 * @ORM\Table(name="gist_pos_trans_item")
 */

class POSTransactionItem
{


    use HasGeneratedID;
    use TrackCreate;



    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $product_id;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $orig_price;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $minimum_price;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $adjusted_price;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $discount_type;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $discount_value;

    /**
     * @ORM\ManyToOne(targetEntity="POSTransaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;

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
     * Set productId
     *
     * @param string $productId
     *
     * @return POSTransactionItems
     */
    public function setProductId($productId)
    {
        $this->product_id = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return string
     */
    public function getProductId()
    {
        return $this->product_id;
    }

    /**
     * Set origPrice
     *
     * @param string $origPrice
     *
     * @return POSTransactionItems
     */
    public function setOrigPrice($origPrice)
    {
        $this->orig_price = $origPrice;

        return $this;
    }

    /**
     * Get origPrice
     *
     * @return string
     */
    public function getOrigPrice()
    {
        return $this->orig_price;
    }

    /**
     * Set minimumPrice
     *
     * @param string $minimumPrice
     *
     * @return POSTransactionItems
     */
    public function setMinimumPrice($minimumPrice)
    {
        $this->minimum_price = $minimumPrice;

        return $this;
    }

    /**
     * Get minimumPrice
     *
     * @return string
     */
    public function getMinimumPrice()
    {
        return $this->minimum_price;
    }

    /**
     * Set adjuestedPrice
     *
     * @param string $adjuestedPrice
     *
     * @return POSTransactionItems
     */
    public function setAdjustedPrice($adjustedPrice)
    {
        $this->adjusted_price = $adjustedPrice;

        return $this;
    }

    /**
     * Get adjuestedPrice
     *
     * @return string
     */
    public function getAdjustedPrice()
    {
        return $this->adjusted_price;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return POSTransactionItems
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set discountType
     *
     * @param string $discountType
     *
     * @return POSTransactionItems
     */
    public function setDiscountType($discountType)
    {
        $this->discount_type = $discountType;

        return $this;
    }

    /**
     * Get discountType
     *
     * @return string
     */
    public function getDiscountType()
    {
        return $this->discount_type;
    }

    /**
     * Set discountValue
     *
     * @param string $discountValue
     *
     * @return POSTransactionItems
     */
    public function setDiscountValue($discountValue)
    {
        $this->discount_value = $discountValue;

        return $this;
    }

    /**
     * Get discountValue
     *
     * @return string
     */
    public function getDiscountValue()
    {
        return $this->discount_value;
    }

    /**
     * Set transaction
     *
     * @param \Gist\POSBundle\Entity\POSTransactions $transaction
     *
     * @return POSTransactionItems
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
}
