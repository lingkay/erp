<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\InventoryBundle\Entity\Product;

/**
 * @ORM\MappedSuperclass
 */
class BOMAsset
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="BillOfMaterial")
     * @ORM\JoinColumn(name="bom_id", referencedColumnName="id")
     */
    protected $bom;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Product", cascade={"persist"})
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    public function setBillOfMaterial(BillOfMaterial $bom)
    {
        $this->bom = $bom;
        return $this;
    }

    public function setProduct(Product $prod)
    {
        $this->product = $prod;
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getProductID()
    {
        if ($this->product == null)
            return 0;

        return $this->product->getID();
    }

    public function getBillOfMaterial()
    {
        return $this->bom;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->id = $this->id;
        $data->quantity = $this->quantity;
        $data->bom_id = $this->getBillOfMaterial()->getID();
        $data->product_id = $this->getProduct()->getID();

        return $data;
    }
}
