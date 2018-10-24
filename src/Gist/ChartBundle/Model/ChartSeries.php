<?php

namespace Gist\ChartBundle\Model;
use stdClass;

class ChartSeries
{
    protected $name;
    protected $data;

    public function __construct($name)
    {
        $this->name = $name;
        $this->data = array();
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function addValue($value)
    {
        $this->data[] = $value;
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getData()
    {
        return $this->data;
    }

    public function toData()
    {
        $data = new stdClass();
        $data->name = $this->name;
        $data->data = $this->data;

        return $data;
    }
}
