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

    public function __construct($inv_account, $product, $qty = 0.00)
    {
        $this->inv_account = $inv_account;
        $this->product = $product;
        $this->quantity = $qty;
    }    

    public function setProduct(Product $prod)
    {
        $this->product = $prod;
        return $this;
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
