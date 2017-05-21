<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use stdClass;

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
}

