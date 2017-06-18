<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use stdClass;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_product")
 */
class Product
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $name;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $product_compositions;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $item_code;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $barcode;

    /**
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\ManyToOne(targetEntity="ProductCategory")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    protected $category;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="photo_id", referencedColumnName="id")
     */
    protected $primary_photo;

    // COST
    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $cost;

    /**
     * @ORM\Column(type="string", length=50)
     */
    protected $cost_currency;

    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $srp;

    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $min_price;

    // Permits
    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $fda_expiration_price;

    /** @ORM\Column(type="datetime") */
    protected $permit_date_from;

    /** @ORM\Column(type="datetime") */
    protected $permit_date_to;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="doc_permit_id", referencedColumnName="id")
     */
    protected $scanned_permit;

    // DESCRIPTIONS
    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $description;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $ingredients;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $directions;


    public function __construct()
    {
    }

    public function getID()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPrimaryPhoto($primary_photo)
    {
        $this->primary_photo = $primary_photo;
        return $this;
    }

    public function getPrimaryPhoto()
    {
        return $this->primary_photo;
    }


    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

    public function getBarcode()
    {
        return $this->barcode;
    }

    public function setItemCode($item_code)
    {
        $this->item_code = $item_code;
        return $this;
    }

    public function getItemCode()
    {
        return $this->item_code;
    }



    public function setProductCompositions($product_compositions)
    {
        $this->product_compositions = $product_compositions;
        return $this;
    }

    public function getProductCompositions()
    {
        return $this->product_compositions;
    }

    public function toData()
    {
        $data = new stdClass();
        $data->id = $this->id;
        $data->name = $this->name;

        return $data;
    }

    /**
     * Set cost
     *
     * @param string $cost
     *
     * @return Product
     */
    public function setCost($cost)
    {
        $this->cost = $cost;

        return $this;
    }

    /**
     * Get cost
     *
     * @return string
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * Set costCurrency
     *
     * @param string $costCurrency
     *
     * @return Product
     */
    public function setCostCurrency($costCurrency)
    {
        $this->cost_currency = $costCurrency;

        return $this;
    }

    /**
     * Get costCurrency
     *
     * @return string
     */
    public function getCostCurrency()
    {
        return $this->cost_currency;
    }

    /**
     * Set srp
     *
     * @param string $srp
     *
     * @return Product
     */
    public function setSRP($srp)
    {
        $this->srp = $srp;

        return $this;
    }

    /**
     * Get srp
     *
     * @return string
     */
    public function getSRP()
    {
        return $this->srp;
    }

    /**
     * Set minPrice
     *
     * @param string $minPrice
     *
     * @return Product
     */
    public function setMinPrice($minPrice)
    {
        $this->min_price = $minPrice;

        return $this;
    }

    /**
     * Get minPrice
     *
     * @return string
     */
    public function getMinPrice()
    {
        return $this->min_price;
    }

    /**
     * Set fdaExpirationPrice
     *
     * @param string $fdaExpirationPrice
     *
     * @return Product
     */
    public function setFDAExpirationPrice($fdaExpirationPrice)
    {
        $this->fda_expiration_price = $fdaExpirationPrice;

        return $this;
    }

    /**
     * Get fdaExpirationPrice
     *
     * @return string
     */
    public function getFDAExpirationPrice()
    {
        return $this->fda_expiration_price;
    }

    /**
     * Set permitDateFrom
     *
     * @param \DateTime $permitDateFrom
     *
     * @return Product
     */
    public function setPermitDateFrom($permitDateFrom)
    {
        $this->permit_date_from = $permitDateFrom;

        return $this;
    }

    /**
     * Get permitDateFrom
     *
     * @return \DateTime
     */
    public function getPermitDateFrom()
    {
        return $this->permit_date_from;
    }

    /**
     * Set scannedPermit
     *
     * @param \DateTime $scannedPermit
     *
     * @return Product
     */
    public function setScannedPermit($scannedPermit)
    {
        $this->scanned_permit = $scannedPermit;

        return $this;
    }

    /**
     * Get scannedPermit
     *
     * @return \DateTime
     */
    public function getScannedPermit()
    {
        return $this->scanned_permit;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set ingredients
     *
     * @param string $ingredients
     *
     * @return Product
     */
    public function setIngredients($ingredients)
    {
        $this->ingredients = $ingredients;

        return $this;
    }

    /**
     * Get ingredients
     *
     * @return string
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Set directions
     *
     * @param string $directions
     *
     * @return Product
     */
    public function setDirections($directions)
    {
        $this->directions = $directions;

        return $this;
    }

    /**
     * Get directions
     *
     * @return string
     */
    public function getDirections()
    {
        return $this->directions;
    }

    /**
     * Set permitDateTo
     *
     * @param \DateTime $permitDateTo
     *
     * @return Product
     */
    public function setPermitDateTo($permitDateTo)
    {
        $this->permit_date_to = $permitDateTo;

        return $this;
    }

    /**
     * Get permitDateTo
     *
     * @return \DateTime
     */
    public function getPermitDateTo()
    {
        return $this->permit_date_to;
    }
}
