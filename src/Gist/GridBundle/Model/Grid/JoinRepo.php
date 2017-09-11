<?php

namespace Gist\GridBundle\Model\Grid;

class JoinRepo
{
    protected $alias;
    protected $field;
    protected $getter;
    protected $type;

    public function __construct($alias, $field, $getter, $type = 'inner')
    {
        $this->alias = $alias;
        $this->field = $field;
        $this->getter = $getter;
        $this->type = $type;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getGetter()
    {
        return $this->getter;
    }
    
    public function getType()
    {
        return $this->type;
    }
}
