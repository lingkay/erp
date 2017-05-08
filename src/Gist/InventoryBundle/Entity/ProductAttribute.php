<?php

namespace Gist\InventoryBundle\Entity;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_product_attribute")
 */
class ProductAttribute
{
    use HasGeneratedID;
    use HasName;


    /** @ORM\Column(type="string", length=200) */
    protected $value;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }
}
