<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\ProductCategory;
use Gist\ValidationException;

class ProductCategoryController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_product_category';
        $this->title = 'Product Category';

        $this->list_title = 'Categories';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new ProductCategory();
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
