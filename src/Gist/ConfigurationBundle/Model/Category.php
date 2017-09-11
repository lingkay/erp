<?php

namespace Gist\ConfigurationBundle\Model;

class Category
{
    protected $name;
    protected $entries;

    public function __construct($name)
    {
        $this->name = $name;
        $this->entries = array();
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function addEntry(DisplayEntry $entry)
    {
        $this->entries[] = $entry;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getEntries()
    {
        return $this->entries;
    }
}
