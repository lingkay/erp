<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_stock_transfer_entry")
 */
class StockTransferEntry
{
    use HasGeneratedID;
    use HasProduct;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="StockTransfer")
     * @ORM\JoinColumn(name="stock_transfer_id", referencedColumnName="id")
     */
    protected $stock_transfer;


    public function __construct()
    {
        $this->debit = 0;
        $this->credit = 0;
    }

    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    public function setStockTransfer(StockTransfer $trans)
    {
        $this->stock_transfer = $trans;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }


    public function getStockTransfer()
    {
        return $this->stock_transfer;
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataHasProduct($data);
        //$this->dataHasInventoryAccount($data);
//        $data->credit = $this->credit;
//        $data->debit = $this->debit;
        $data->stock_transfer_id = $this->getStockTransfer()->getID();

        return $data;
    }
}

