<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Entity\Product;
use Gist\ContactBundle\Entity\Contact;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\TrackUpdate;
use Gist\ContactBundle\Template\Entity\HasContact;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_supplier_product")
 */
class SupplierProduct
{
    

    use HasGeneratedID;
    // use TrackCreate;
    // use TrackUpdate;
    //use HasContact;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Product") 
     * @ORM\JoinColumn(name="product", referencedColumnName="id")
     */
    protected $product;

    
    /** @ORM\Column(type="decimal", precision=13, scale=2, nullable=true) */
    protected $price;


    public function __construct()
    {
        
        $this->initHasGeneratedID();

        // $this->initTrackCreate();
        // $this->initTrackUpdate();
    }

    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }


    public function setProduct(Product $product)
    {
        $this->product = $product;
        return $this;
    }

    public function getProduct()
    {
        return $this->product;
    }

    
    public function toData()
    {
        $data = new stdClass();

        $data->id = $this->id;
        
        // traits
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataTrackUpdate($data);

        // TODO: update for type

        return $data;
    }
}
