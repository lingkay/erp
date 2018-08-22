<?php

namespace Gist\ChartBundle\Model;

class Chart
{
    protected $categories;
    protected $series;
    protected $yaxis;

    public function __construct()
    {
        $this->categories = array();
        $this->series = array();
        $this->yaxis = "Amount";
    }

    public function setYAxis($yaxis)
    {
        $this->yaxis = $yaxis;
        return $this;
    }

    public function getYAxis()
    {
        return $this->yaxis;
    }
    
    public function addCategory($cat)
    {
        $this->categories[] = $cat;
        return $this;
    }

    public function addSeries(ChartSeries $series)
    {
        $this->series[] = $series;
        return $this;
    }

    public function getCategories()
    {
        return $this->categories;
    }

    public function getSeriesCollection()
    {
        return $this->series;
    }
}
