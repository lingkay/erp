<?php

namespace Gist\ChartBundle\Model;

class Chart
{
    protected $categories;
    protected $series;

    public function __construct()
    {
        $this->categories = array();
        $this->series = array();
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
