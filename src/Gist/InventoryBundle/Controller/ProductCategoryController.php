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

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('b', 'brand', 'getBrand'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Brand', 'getName', 'name','b'),
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);

        if (isset($data['brand'])) {
            $brand = $em->getRepository('GistInventoryBundle:Brand')->find($data['brand']);
            $o->setBrand($brand);
        }
    }

    protected function padFormParams(&$params, $product = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['brand'] = $product->getBrand();
        $params['brand_opts'] = $this->getBrandOptions();
        

        return $params;
    }

    public function getBrandOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistInventoryBundle:Brand',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }
}
