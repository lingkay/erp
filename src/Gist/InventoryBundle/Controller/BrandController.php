<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Brand;
use Gist\ValidationException;

class BrandController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_brand';
        $this->title = 'Brand';

        $this->list_title = 'Brands';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Brand();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
    }
}
