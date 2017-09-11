<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_service_task")
 */
class ServiceTask
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=80)
     */
    protected $name;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     */
    protected $sell_price;

    /**
     * @ORM\Column(type="decimal", precision=13, scale=2)
     */
    protected $cost_price;

    /**
     * @ORM\Column(type="integer")
     */
    protected $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="Product")
     * #ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function __construct()
    {
        $this->sell_price = 0.00;
        $this->cost_price = 0.00;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setSellPrice($price)
    {
        $this->sell_price = $price;
        return $this;
    }

    public function setCostPrice($price)
    {
        $this->cost_price = $price;
        return $this;
    }

    public function setProduct(Product $prod)
    {
        $this->product = $prod;
        $this->product_id = $prod->getID();
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSellPrice()
    {
        return $this->sell_price;
    }

    public function getCostPrice()
    {
        return $this->cost_price;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->id = $this->id;
        $data->name = $this->name;
        $data->sell_price = $this->sell_price;
        $data->cost_price = $this->cost_price;
        $data->product_id = $this->product_id;

        return $data;
    }
}
