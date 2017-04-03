<?php

namespace Gist\MenuBundle\Model\Menu;

use Iterator;

// iterable collection of menu items
class Collection implements Iterator
{
    protected $position = 0;
    protected $array;
    protected $index_array;

    public function __construct()
    {
        $this->position = 0;
        $this->array = array();
        $this->index_array = array();
    }

    // iterator stuff
    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->array[$this->index_array[$this->position]];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        return ++$this->position;
    }

    public function valid()
    {
        return isset($this->index_array[$this->position]);
    }
    // end of iterator stuff

    public function add(Item $mi)
    {
        $id = $mi->getID();
        $this->array[$id] = $mi;
        $this->index_array[] = $id;
        return $this;
    }

    public function get($id)
    {
        if (isset($this->array[$id]))
            return $this->array[$id];

        return null;
    }

    public function unselectAll()
    {
        foreach ($this->array as $mi)
            $mi->setSelected(false, false);

        return $this;
    }
}
