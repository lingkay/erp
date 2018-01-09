<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_damaged_items_entry")
 */
class DamagedItemsEntry
{
    use HasGeneratedID;
    use HasProduct;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="DamagedItems")
     * @ORM\JoinColumn(name="damaged_items_id", referencedColumnName="id")
     */
    protected $damaged_items;


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

    public function setDamagedItems(DamagedItems $trans)
    {
        $this->damaged_items = $trans;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getDamagedItems()
    {
        return $this->damaged_items;
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataHasProduct($data);
        $data->stock_transfer_id = $this->getStockTransfer()->getID();

        return $data;
    }
}

