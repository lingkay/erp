<?php

namespace Gist\InventoryBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\InventoryBundle\Entity\Product;

trait HasProduct
{
    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function setProduct(Product $prod)
    {
        $this->product = $prod;
        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function initHasProduct()
    {
        $this->product = null;
    }

    public function dataHasProduct($data)
    {
        $data->product = $this->product->toData();
    }
}
