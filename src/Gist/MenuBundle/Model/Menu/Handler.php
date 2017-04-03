<?php

namespace Gist\MenuBundle\Model\Menu;

class Handler
{
    protected $container;
    protected $menu;
    protected $index;
    protected $selected;

    public function __construct($container)
    {
        $this->container = $container;

        // generate and cache
        $gen = new Generator($container);
        $this->menu = $gen->generate();
        $this->index = $gen->getIndex();
    }

    public function setSelected($route)
    {
        if ($route == null)
            return $this;

        $sel = $this->index->get($route);
        if ($sel == null)
            return $this;
        $sel->setSelected();

        $this->selected = $sel;

        return $this;
    }

    public function getMenu()
    {
        return $this->menu;
    }

    public function getSelected()
    {
        return $this->selected;
    }
}
