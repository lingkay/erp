<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_counting_entry")
 */
class CountingEntry
{
    use HasGeneratedID;
    use HasProduct;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="Counting")
     * @ORM\JoinColumn(name="counting_id", referencedColumnName="id")
     */
    protected $counting;

    public function __construct()
    {

    }

    public function setQuantity($qty)
    {
        $this->quantity = $qty;
        return $this;
    }

    public function setCounting(Counting $trans)
    {
        $this->counting = $trans;
        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }

    public function getCounting()
    {
        return $this->counting;
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataHasProduct($data);
        $data->counting_id = $this->getStockTransfer()->getID();

        return $data;
    }
}

