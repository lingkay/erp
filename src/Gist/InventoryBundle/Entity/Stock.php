<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasQuantity;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_stock")
 */
class Stock
{
    use HasQuantity;

    // NOTE: cannot use HasProduct and HasInventoryAccount trait since we're
    //       using it as primary key

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    /**
     * @ORM\Id
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Account")
     * @ORM\JoinColumn(name="inv_account_id", referencedColumnName="id")
     */
    protected $inv_account;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $min_stock;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $max_stock;

    public function __construct($inv_account, $product, $qty = 0.00)
    {
        $this->inv_account = $inv_account;
        $this->product = $product;
        $this->quantity = $qty;
    }

    public function getID()
    {
        return $this->product->getID();
    }

    public function setMinStock($min_stock)
    {
        $this->min_stock = $min_stock;
        return $this;
    }

    public function getMinStock()
    {
        return $this->min_stock;
    }

    public function setMaxStock($max_stock)
    {
        $this->max_stock = $max_stock;
        return $this;
    }

    public function getMaxStock()
    {
        return $this->max_stock;
    }

    public function setProduct(Product $prod)
    {
        $this->product = $prod;
        return $this;
    }

    public function getQuantityColored()
    {
        if ($this->quantity <= $this->min_stock || $this->quantity >= $this->max_stock) {
            return '<span style="color: red;">'.number_format($this->quantity, 2).'</span>';
        } else {
            return '<span>'.number_format($this->quantity, 2).'</span>';
        }
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function setInventoryAccount(Account $account)
    {
        $this->inv_account = $account;
        return $this;
    }

    public function getInventoryAccount()
    {
        return $this->inv_account;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->inv_account = $this->inv_account->toData();
        $data->product = $this->product->toData();

        $this->dataHasQuantity($data);

        return $data;
    }
}
