<?php

namespace Gist\ConfigurationBundle\Model;

class DisplayEntry
{
    const TYPE_SELECT           = 1;

    protected $id;
    protected $value;
    protected $label;
    protected $type;
    protected $options;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getOptions()
    {
        return $this->options;
    }
}
