<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\ProductType;
use Gist\ValidationException;

class ProductTypeController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_product_type';
        $this->title = 'Product Type';

        $this->list_title = 'Types';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new ProductType();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }



    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name')
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);

    }

    protected function padFormParams(&$params, $product = null)
    {
        $em = $this->getDoctrine()->getManager();


        return $params;
    }

}
