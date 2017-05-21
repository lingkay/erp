<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Product;
use Gist\ValidationException;

class ProductController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_prod';
        $this->title = 'Product';

        $this->list_title = 'Products';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Product();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('c', 'category', 'getCategory'),
            $grid->newJoin('b', 'brand', 'getBrand'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Category', 'getName', 'name','c'),
            $grid->newColumn('Brand', 'getName', 'name','b'),
        );
    }

    protected function padFormParams(&$params, $product = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['type_opts'] = array(
            'single' => 'Single Product',
            'package' => 'Package'
        );

        $params['ptype'] = 'single';

        $params['item_opts'] = $this->getProductOptions();
        $params['brand_opts'] = $this->getBrandOptions();
        $params['category_opts'] = $this->getCategoryOptions();

        $params['category'] = $product->getCategory();
        $params['brand'] = $product->getBrand();

        if ($product->getProductCompositions() == null) {
            $params['product_composition'] = null;
            $params['ptype'] = 'single';
        } else {
            $product_composition = array();
            foreach (explode('&', $product->getProductCompositions()) as $piece) {
                $product_composition[] = explode('~', $piece);
            }

            $params['product_composition'] = $product_composition;
            $params['ptype'] = 'package';
        }

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);

        if (isset($data['brand'])) {
            $brand = $em->getRepository('GistInventoryBundle:Brand')->find($data['brand']);
            $o->setBrand($brand);
        }

        if (isset($data['category'])) {
            $category = $em->getRepository('GistInventoryBundle:ProductCategory')->find($data['category']);
            $o->setCategory($category);
        }

        if (isset($data['item_code'])) {
            $o->setItemCode($data['item_code']);
        }

        if (isset($data['barcode'])) {
            $o->setBarcode($data['barcode']);
        }

        //parse items given
        if (isset($data['item_id'])) {
             $items_given = [];
            foreach ($data['item_id'] as $i => $item_id) {
                $items_given[] = array($item_id, $data['qty'][$i]);
            }

            $items_given_formatted = implode("&",array_map(function($a) {return implode("~",$a);},$items_given));


            $o->setProductCompositions($items_given_formatted);
        } else {
            $o->setProductCompositions(null);
        }
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

    public function getProductOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistInventoryBundle:Product',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
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

    public function getCategoryOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistInventoryBundle:ProductCategory',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }
}
