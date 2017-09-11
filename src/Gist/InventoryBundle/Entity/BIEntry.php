<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\CoreBundle\Template\Entity\HasQuantity;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_bi_entry")
 */
class BIEntry
{
    use HasGeneratedID;
    use HasProduct;
    use HasQuantity;

    /**
     * @ORM\ManyToOne(targetEntity="BorrowedTransaction", inversedBy="entries")
     * @ORM\JoinColumn(name="borrowed_id", referencedColumnName="id")
     */
    protected $borrowed;

    /**
     * @ORM\OneToMany(targetEntity="ReturnedItem", mappedBy="bientry", cascade={"persist", "remove"})
     */
    protected $returned;


    public function __construct()
    {
        $this->initHasQuantity();        
        $this->initHasGeneratedID();
    }

    public function setDateReturned(DateTime $date)
    {
        $this->date_returned = $date;
        return $this;
    }

    public function setBorrowed(BorrowedTransaction $borrowed)
    {
        $this->borrowed = $borrowed;
        $this->borrowed_id = $borrowed->getID();
        return $this;
    }

    public function getReturned()
    {
        return $this->returned;
    }

    public function getBorrowed()
    {
        return $this->borrowed;
    }

    public function getReturnedCount()
    {
        $counter = 0;
        foreach ($this->getReturned() as $returned)
        {
            $counter += $returned->getQuantity();
        }

        return $counter;
    }


    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataHasProduct($data);
        $this->dataHasQuantity($data);
        $data->borrowed_id = $this->getBorrowed()->getID();

        return $data;
    }

}