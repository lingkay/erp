<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BOMDimension
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(type="string", length=50) */
    protected $name;

    /** @ORM\Column(type="string", length=25) */
    protected $shortcode;

    /**
     * @ORM\ManyToOne(targetEntity="BOMTemplate", inversedBy="dimensions")
     * @ORM\JoinColumn(name="template_id", referencedColumnName="id")
     */
    protected $template;

    public function __construct($name, $shortcode)
    {
        $this->name = $name;
        $this->shortcode = $shortcode;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function setShortcode($shortcode)
    {
        $this->shortcode = $shortcode;
        return $this;
    }

    public function setTemplate(BOMTemplate $template)
    {
        $this->template = $template;
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

    public function getShortcode()
    {
        return $this->shortcode;
    }

    public function getTemplate()
    {
        return $this->template;
    }

    public function toData()
    {
        $data = new \stdClass();

        $data->id = $this->id;
        $data->name = $this->name;
        $data->shortcode = $this->shortcode;
        $data->template_id = $this->getTemplate()->getID();

        return $data;
    }
}

