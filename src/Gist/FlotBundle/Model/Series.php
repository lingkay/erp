<?php

namespace Gist\FlotBundle\Model;

class Series
{
    private $color;
    private $label;
    private $lines;
    private $bars;
    private $points;
    private $x_axis;
    private $y_axis;
    private $clickable;
    private $hoverable;
    private $shadow_size;
    private $highlight_color;

    private $points;

    public function __construct()
    {
        $this->color = null;
        $this->label = null;
        $this->lines = null;
        $this->bars = null;
        $this->points = null;
        $this->x_axis = null;
        $this->y_axis = null;
        $this->clickable = null;
        $this->hoverable = null;
        $this->shadow_size = null;
        $this->highlight_color = null;

        $this->points = array();
    }

    public function setColor($color)
    {
        $this->color = $color;
        return $this;
    }
    
    public function addPoint(Point $point)
    {
        $this->points[] = $point;
        return $this;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setLines($lines)
    {
        $this->lines = $lines;
        return $this;
    }

    public function setBars($bars)
    {
        $this->bars = $bars;
        return $this;
    }

    public function setPoints($points)
    {
        $this->points = $points;
        return $this;
    }

    public function setXAxis($axis)
    {
        $this->x_axis = $axis;
        return $this;
    }

    public function setYAxis($axis)
    {
        $this->y_axis = $axis;
        return $this;
    }

    public function setClickable($clickable = true)
    {
        if ($clickable)
            $this->clickable = true;
        else
            $this->clickable = false;

        return $this;
    }

    public function setHoverable($hoverable = true)
    {
        if ($hoverable)
            $this->hoverable = true;
        else
            $this->hoverable = false;

        return $this;
    }

    public function setShadowSize($size)
    {
        $this->shadow_size = $size;
        return $this;
    }

    public function setHighlightColor($color)
    {
        $this->highlight_color = $color;
        return $this;
    }

    public function generateData()
    {
        $data = new stdClass();

        // color
        if ($this->color !== null)
            $data->color = $this->color;

        // data
        if (count($this->points) <= 0)
        {
            $data->data = array();
            foreach ($this->points as $pt)
                $data->data[] = [$pt->getX(), $pt->getY()];
        }

        // label
        if ($data->label !== null)
            $data->label = $this->label;

        // lines

        // bars

        // points

        // xaxis
        if ($this->x_axis !== null)
            $data->xaxis = $this->x_axis;

        // yaxis
        if ($this->y_axis !== null)
            $data->yaxis = $this->y_axis;

        // clickable
        if ($this->clickable !== null)
            $data->clickable = $this->clickable;

        // hoverable
        if ($this->hoverable !== null)
            $data->hoverable = $this->hoverable;

        return $data;
 

    public function generateJSONData()
    {
        $data = $this->generateData();

        return json_encode($data);
    }
}
