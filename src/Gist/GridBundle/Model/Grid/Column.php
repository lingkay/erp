<?php

namespace Gist\GridBundle\Model\Grid;

class Column
{
    protected $label;
    protected $get_method;
    protected $order_field;
    protected $format_callback;
    protected $sortable;
    protected $searchable;
    protected $object_alias;

    public function __construct($label, $get_method, $order_field = null, $object_alias = 'o')
    {
        $this->label = $label;
        $this->get_method = $get_method;
        $this->order_field = $order_field;
        $this->object_alias = $object_alias;

        $this->format_callback = null;
        $this->sortable = true;
        $this->searchable = true;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public function setGetterMethod($getter)
    {
        $this->get_method($getter);
        return $this;
    }

    public function setOrderField($order_field)
    {
        $this->order_field = $order_field;
        return $this;
    }

    public function setObjectAlias($object_alias)
    {
        $this->object_alias = $object_alias;
        return $this;
    }

    public function setFormatCallback($callback = null)
    {
        $this->format_callback = $callback;
        return $this;
    }

    public function setSortable($sortable = true)
    {
        $this->sortable = $sortable;
        return $this;
    }

    public function setSearchable($searchable = true)
    {
        $this->searchable = $searchable;
        return $this;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function getObjectAlias()
    {
        return $this->object_alias;
    }

    public function isSortable()
    {
        if ($this->sortable)
            return true;
        return false;
    }

    public function isSearchable()
    {
        if ($this->searchable)
            return true;
        return false;
    }

    public function getOrderField()
    {
        return $this->order_field;
    }

    public function getFullOrderField()
    {
        return $this->object_alias . '.' . $this->order_field;
    }

    public function getValue($object)
    {
        if($object != null){
            $method = $this->get_method;
            $value = $object->$method();

            return $this->format($value);
        } 
            return '';
    }

    public function format($value)
    {
        if ($this->format_callback == null)
            return $value;

        return call_user_func($this->format_callback, $value);
    }
}
