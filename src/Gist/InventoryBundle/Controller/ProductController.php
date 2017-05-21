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

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['type_opts'] = array(
            'single' => 'Single Product',
            'composition' => 'Composition'
        );

        $params['ptype'] = 'single';

        $params['item_opts'] = $this->getProductOptions();

        if ($user->getProductCompositions() == null) {
            $params['product_composition'] = null;
            $params['ptype'] = 'single';
        } else {
            $product_composition = array();
            foreach (explode('&', $user->getProductCompositions()) as $piece) {
                $product_composition[] = explode('~', $piece);
            }

            $params['product_composition'] = $product_composition;
            $params['ptype'] = 'composition';
        }

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);


        //parse items given
        if (isset($data['item_id'])) {
             $items_given = [];
            foreach ($data['item_id'] as $i => $item_id) {
                $items_given[] = array($item_id, $data['qty'][$i]);
            }

            // print_r($items_given);
            // echo "<br><br>";
            $items_given_formatted = implode("&",array_map(function($a) {return implode("~",$a);},$items_given));
            // print_r($out);
            // echo "<br><br>";
            // $result = array();
            // foreach (explode('&', $out) as $piece) {
            //     $result[] = explode('~', $piece);
            // }
            // print_r($result);
            // die();

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
}
