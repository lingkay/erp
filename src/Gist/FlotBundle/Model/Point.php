<?php

namespace Gist\FlotBundle\Model;

class Point
{
    private $x;
    private $y;

    public function __construct()
    {
        $this->x = 0;
        $this->y = 0;
    }

    public function setX($x)
    {
        $this->x = $x;
        return $this;
    }

    public function setY($y)
    {
        $this->y = $y;
        return $this;
    }

    public function getX()
    {
        return $this->x;
    }

    public function getY()
    {
        return $this->y;
    }
}
