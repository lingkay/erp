<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\InventoryBundle\Entity\ProductGroup;

/**
 * @ORM\Entity
 */
class BOMTemplate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", length=50) */
    protected $name;

    /** @ORM\Column(type="string", length=80) */
    protected $output_code;

    /** @ORM\Column(type="string", length=80) */
    protected $output_name;

    /** @ORM\Column(type="integer") */
    protected $prodgroup_id;

    /** @ORM\Column(type="string", length=20) */
    protected $uom;

    /**
     * @ORM\OneToMany(targetEntity="BOMDimension", mappedBy="template", cascade={"persist"})
     */
    protected $dimensions;

    /**
     * @ORM\OneToMany(targetEntity="BOMMaterial", mappedBy="template", cascade={"persist"})
     */
    protected $materials;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\ProductGroup")
     * @ORM\JoinColumn(name="prodgroup_id", referencedColumnName="id")
     */
    protected $prodgroup;

    public function __construct()
    {
        $this->dimensions = new ArrayCollection();
        $this->materials = new ArrayCollection();
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setOutputCode($code)
    {
        $this->output_code = $code;
        return $this;
    }

    public function setOutputName($name)
    {
        $this->output_name = $name;
        return $this;
    }

    public function setUnitOfMeasure($uom)
    {
        $this->uom = $uom;
        return $this;
    }

    public function setProductGroup(ProductGroup $prodgroup)
    {
        $this->prodgroup = $prodgroup;
        $this->prodgroup_id = $prodgroup->getID();
        return $this;
    }

    public function addDimension(BOMDimension $dim)
    {
        $this->dimensions->add($dim);
        $dim->setTemplate($this);
        return $this;
    }

    public function clearDimensions()
    {
        $this->dimensions->clear();
        return $this;
    }

    public function addMaterial(BOMMaterial $mat)
    {
        $this->materials->add($mat);
        $mat->setTemplate($this);
        return $this;
    }

    public function clearMaterials()
    {
        $this->materials->clear();
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getOutputCode()
    {
        return $this->output_code;
    }

    public function getOutputName()
    {
        return $this->output_name;
    }

    public function getUnitOfMeasure()
    {
        return $this->uom;
    }

    public function getProductGroupID()
    {
        return $this->prodgroup_id;
    }

    public function getProductGroup()
    {
        return $this->prodgroup;
    }

    public function getDimensions()
    {
        return $this->dimensions;
    }

    public function getMaterials()
    {
        return $this->materials;
    }

    public function toData()
    {
        $data = new \stdClass();
        $data->id = $this->id;
        $data->name = $this->name;
        $data->output_code = $this->output_code;
        $data->output_name = $this->output_name;
        $data->uom = $this->uom;
        $data->prodgroup_id = $this->prodgroup_id;

        $dims = array();
        foreach ($this->dimensions as $dim)
            $dims[] = $dim->toData();
        $data->dimensions = $dims;

        $mats = array();
        foreach ($this->materials as $mat)
            $mats[] = $mat->toData();
        $data->materials = $mats;

        return $data;
    }
}
