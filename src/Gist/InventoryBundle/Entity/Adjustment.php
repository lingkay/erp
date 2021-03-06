<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\CoreBundle\Template\Entity\HasQuantity;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Gist\InventoryBundle\Entity\Transaction;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_adjustment")
 */
class Adjustment
{
    use HasGeneratedID;
    use HasProduct;    
    use HasQuantity;
    use HasInventoryAccount;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction_id;

    /** @ORM\Column(type="datetime") */
    protected $date_create;

    /** @ORM\Column(type="string", nullable=true) */
    protected $remarks;


    public function __construct()
    {
        //$this->date_create = new DateTime();
    }

    public function setTransaction(Transaction $trans)
    {
        $this->transaction_id = $trans;
        return $this;
    }

    public function getTransaction()
    {
        return $this->transaction_id;
    }

    public function setDateCreate($date)
    {
        $this->date_create = $date;
        return $this;
    }

    public function getDateCreate()
    {
        return $this->date_create;
    }

    public function setRemarks($remarks)
    {
        $this->remarks = $remarks;
        return $this;
    }

    public function getRemarks()
    {
        return $this->remarks;
    }

    public function getDateCreateFormatted()
    {
        return $this->date_create->format('d/m/Y');
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasQuantity($data);
        $this->dataHasProduct($data);
        $this->dataHasInventoryAccount($data);

        $data->transaction_id = $this->transaction_id;
        $data->date_create = $this->date_create;
        $data->lot_id = $this->getLot()->getID();


        return $data;
    }
}
