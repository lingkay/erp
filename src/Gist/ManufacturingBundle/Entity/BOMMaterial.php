<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\InventoryBundle\Entity\Product;

/**
 * @ORM\Entity
 */
class BOMMaterial
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", length=100) */
    protected $formula;

    /** @ORM\Column(type="integer") */
    protected $product_id;

    /**
     * @ORM\ManyToOne(targetEntity="BOMTemplate", inversedBy="materials")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $template;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Product")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     */
    protected $product;

    public function __construct()
    {
    }

    public function setFormula($formula)
    {
        $this->formula = $formula;
        return $this;
    }

    public function setTemplate(BOMTemplate $template)
    {
        $this->template = $template;
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

    public function getFormula()
    {
        return $this->formula;
    }

    public function getProductID()
    {
        return $this->product_id;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function getProduct()
    {
        return $this->product;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->id = $this->id;
        $data->formula = $this->formula;
        $data->product_id = $this->product_id;
        $data->template_id = $this->getTemplate()->getID();

        return $data;
    }
}

