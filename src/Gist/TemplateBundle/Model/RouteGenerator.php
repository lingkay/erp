<?php

namespace Gist\TemplateBundle\Model;

class RouteGenerator
{
    protected $prefix;

    public function __construct($prefix)
    {
        $this->prefix = $prefix;
    }

    public function getList()
    {
        return $this->prefix . '_index';
    }

    public function getAdd()
    {
        return $this->prefix . '_add_form';
    }

    public function getEdit()
    {
        return $this->prefix . '_edit_form';
    }

    public function getDelete()
    {
        return $this->prefix . '_delete';
    }

    public function getGrid()
    {
        return $this->prefix . '_grid';
    }

    public function getAjaxGet()
    {
        return $this->prefix . '_ajax_get';
    }
}
