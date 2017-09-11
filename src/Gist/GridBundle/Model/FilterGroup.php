<?php

namespace Gist\GridBundle\Model;

class FilterGroup
{
    protected $filters;

    public function __construct()
    {
        $this->filters = array();
    }

    public function __call($name, $args)
    {
        $this->filters[] = array($name => $args);
        return $this;
    }

    public function apply($object)
    {
        foreach ($this->filters as $fil)
        {
            foreach($fil as $name => $args)
            {
                call_user_func_array(array($object, $name), $args);
            }
        }
    }
}
