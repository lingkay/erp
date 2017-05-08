<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\TrackUpdate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(
 *      name="inv_product",
 *      indexes={@ORM\Index(name="prod_sale_idx", columns={"flag_sale"}),
 *          @ORM\Index(name="prod_pur_idx", columns={"flag_purchase"})}
 * )
 */
class Product
{
    const TYPE_RAW_MATERIAL         = 1; // to be used as raw materials for manufacturing
    const TYPE_FINISHED_GOOD        = 2; // result of manufacturing
    const TYPE_INVENTORY            = 3; // anything classified under inventory but not raw materials or finished goods
    const TYPE_SERVICE              = 4; // services 
    const TYPE_VIRTUAL              = 5; // virtual products
    const TYPE_FIXED_ASSET          = 6; // fixed assets / capital goods. PP&E
    const TYPE_CUSTOMER_ITEM        = 7; // customer items given by customer and to be returned after servicing.
    const TYPE_OTHER                = 100; // anything else that's not covered (should not reach this)

    use HasGeneratedID;
    use HasName;
    use TrackCreate;
    use TrackUpdate;
    
    /** @ORM\Column(type="string", length=25, nullable=false) */
    protected $sku;

    /** @ORM\Column(type="string", length=20) */
    protected $uom;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_sale;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_purchase;

     /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_perishable;
    
    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $price_sale;

    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $price_purchase;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $stock_min;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $stock_max;

    /**
     * @ORM\ManyToOne(targetEntity="ProductGroup", inversedBy="products")
     * @ORM\JoinColumn(name="prodgroup_id", referencedColumnName="id")
     */
    protected $prodgroup;

    /**
     * @ORM\ManyToOne(targetEntity="Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="variants")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     **/
    protected $parent;

    /** @ORM\OneToMany(targetEntity="Product", mappedBy="parent") */
    protected $variants;

    /** @ORM\OneToMany(targetEntity="ProductAttribute", mappedBy="product", cascade={"persist"}) */
    protected $attributes;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $attribute_hash;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $type_id;


    public function __construct()
    {
        $this->price_sale = 0.00;
        $this->price_purchase = 0.00;

        $this->stock_min = 0.00;
        $this->stock_max = 0.00;

        $this->flag_sale = false;
        $this->flag_purchase = false;
        $this->flag_perishable = false;

        $this->parent = null;
        $this->variants = new ArrayCollection();

        $this->attributes = new ArrayCollection();
        $this->attribute_hash = array();

        $this->type_id = self::TYPE_INVENTORY;

        $this->initHasGeneratedID();
        $this->initHasName();
        $this->initTrackCreate();
        $this->initTrackUpdate();
    }

    public function setSKU($sku)
    {
        $this->sku = $sku;
        return $this;
    }

    public function setUnitOfMeasure($uom)
    {
        $this->uom = $uom;
        return $this;
    }

    public function setFlagSale($flag = true)
    {
        $this->flag_sale = $flag;
        return $this;
    }

    public function setFlagPurchase($flag = true)
    {
        $this->flag_purchase = $flag;
        return $this;
    }
    
    public function setFlagPerishable($flag = true)
    {
        $this->flag_perishable = $flag;
        return $this;
    }

    public function setPriceSale($price)
    {
        $this->price_sale = $price;
        return $this;
    }

    public function setPricePurchase($price)
    {
        $this->price_purchase = $price;
        return $this;
    }

    public function setProductGroup(ProductGroup $group)
    {
        $this->prodgroup = $group;
        return $this;
    }

    public function setBrand(Brand $brand)
    {
        $this->brand = $brand;
        return $this;
    }

    public function setStockMin($stock)
    {
        $this->stock_min = $stock;
        return $this;
    }

    public function setStockMax($stock)
    {
        $this->stock_max = $stock;
        return $this;
    }

    public function getSKU()
    {
        return $this->sku;
    }

    public function getUnitOfMeasure()
    {
        return $this->uom;
    }

    public function isPerishable()
    {
        return $this->flag_perishable;
    }

    public function canSell()
    {
        return $this->flag_sale;
    }

    public function canPurchase()
    {
        return $this->flag_purchase;
    }

    public function getPriceSale()
    {
        return $this->price_sale;
    }

    public function getPricePurchase()
    {
        return $this->price_purchase;
    }

    public function getProductGroup()
    {
        return $this->prodgroup;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function getStockMin()
    {
        return $this->stock_min;
    }

    public function getStockMax()
    {
        return $this->stock_max;
    }

    public function setParent(Product $prod)
    {
        $this->parent = $prod;
        return $this;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function addVariant(Product $prod)
    {
        $prod->setParent($this);
        $this->variants->add($prod);
        return $this;
    }

    public function getVariants()
    {
        return $this->variants;
    }

    public function addVariantAttribute(ProductAttribute $attr)
    {
        $this->attributes->add($attr);
        return $this;
    }

    public function getVariantAttributes()
    {
        return $this->attributes;
    }
    
    public function getAttributeValue($name)
    {
        foreach($this->attributes as $attribute)
        {
            if($attribute->getName() == $name)
            {
                return $attribute->getValue();
            }
        }

        return null;
    }
    
    public function isVariant()
    {
        if ($this->parent == null)
            return false;
        return true;
    }

    public function setTypeID($type)
    {
        // TODO: check if valid type
        $this->type_id = $type;
        return $this;
    }

    public function getTypeID()
    {
        return $this->type_id;
    }

    public function getTypeText()
    {
        switch ($this->type_id)
        {
            case self::TYPE_RAW_MATERIAL:
                return 'Raw Material';
            case self::TYPE_FINISHED_GOOD:
                return 'Finished Good';
            case self::TYPE_INVENTORY:
                return 'Inventory';
            case self::TYPE_FIXED_ASSET:
                return 'Fixed Asset';

            // TODO: the others
        }

        return 'Unknown';
    }

    public function toData()
    {
        $data = new stdClass();

        $data->id = $this->id;
        $data->sku = $this->sku;
        $data->name = $this->name;        
        $data->prodgroup_id = $this->prodgroup->getID();
        $data->uom = $this->uom;
        $data->flag_sale = $this->flag_sale;
        $data->flag_purchase = $this->flag_purchase;
        $data->price_sale = $this->price_sale;
        $data->price_purchase = $this->price_purchase;

        // brand
        if ($this->brand == null)
            $data->brand_id = null;
        else
            $data->brand_id = $this->brand->getID();

        // traits
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);
        $this->dataTrackCreate($data);
        $this->dataTrackUpdate($data);

        // TODO: update for type

        return $data;
    }
}
