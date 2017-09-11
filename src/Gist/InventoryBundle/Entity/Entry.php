<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_entry")
 */
class Entry
{
    use HasGeneratedID;
    use HasInventoryAccount;
    use HasProduct;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $credit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    protected $debit;

    /**
     * @ORM\ManyToOne(targetEntity="Transaction")
     * @ORM\JoinColumn(name="transaction_id", referencedColumnName="id")
     */
    protected $transaction;


    public function __construct()
    {
        $this->debit = 0;
        $this->credit = 0;
        $this->initHasInventoryAccount();
    }

    public function setCredit($qty)
    {
        $this->credit = $qty;
        return $this;
    }

    public function setDebit($qty)
    {
        $this->debit = $qty;
        return $this;
    }

    public function setTransaction(Transaction $trans)
    {
        $this->transaction = $trans;
        return $this;
    }

    public function getCredit()
    {
        return $this->credit;
    }

    public function getDebit()
    {
        return $this->debit;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataHasProduct($data);
        $this->dataHasInventoryAccount($data);
        $data->credit = $this->credit;
        $data->debit = $this->debit;
        $data->transaction_id = $this->getTransaction()->getID();

        return $data;
    }
}
