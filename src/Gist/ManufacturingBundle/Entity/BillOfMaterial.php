<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class BillOfMaterial
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", length=50) */
    protected $name;

    /**
     * @ORM\OneToMany(targetEntity="BOMInput", mappedBy="bom", cascade={"persist"})
     */
    protected $inputs;

    /**
     * @ORM\OneToMany(targetEntity="BOMOutput", mappedBy="bom", cascade={"persist"})
     */
    protected $outputs;

    public function __construct()
    {
        $this->inputs = new ArrayCollection();
        $this->outputs = new ArrayCollection();
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function addInput(BOMInput $input)
    {
        $this->inputs->add($input);
        $input->setBillOfMaterial($this);
        return $this;
    }

    public function clearInputs()
    {
        $this->inputs->clear();
        return $this;
    }

    public function addOutput(BOMOutput $output)
    {
        $this->outputs->add($output);
        $output->setBillOfMaterial($this);
        return $this;
    }

    public function clearOutputs()
    {
        $this->outputs->clear();
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

    public function getInputs()
    {
        return $this->inputs;
    }

    public function getOutputs()
    {
        return $this->outputs;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->id = $this->id;
        $data->name = $this->name;
        
        $inputs = array();
        foreach ($this->inputs as $input)
            $inputs[] = $input->toData();
        $data->inputs = $inputs;

        $outputs = array();
        foreach ($this->outputs as $output)
            $outputs[] = $output->toData();
        $data->outputs = $outputs;

        return $data;
    }
}

