<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Model\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\ValidationException;

use DateTime;
use SplFileObject;
use LimitIterator;

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
            $grid->newColumn('Item Code', 'getItemCode', 'item_code'),
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Description', 'getDescription', 'description'),
            $grid->newColumn('Cost', 'getCostFMTD', 'cost'),
            $grid->newColumn('SRP', 'getSRPFMTD', 'srp'),
            $grid->newColumn('Min. Price', 'getMinPriceFMTD', 'min_price'),
            // $grid->newColumn('Status', 'getStatus', 'status'),
        );
    }

    protected function padFormParams(&$params, $product = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');

        $params['currency_opts'] = $am->getCurrencyOptions();

        $params['class_opts'] = array(
            'single' => 'Single',
            'package' => 'Package'
        );

        // $params['ptype'] = 'single';
        $params['type_opts'] = $this->getTypeOptions();

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
        // var_dump($data);
        // die();
        $media = $this->get('gist_media');
        $em = $this->getDoctrine()->getManager();
        $o->setName($data['name']);

        if($data['photo']!=0 && $data['photo'] != ""){
            $o->setPrimaryPhoto($media->getUpload($data['photo']));
        }

        if (isset($data['brand'])) {
            $brand = $em->getRepository('GistInventoryBundle:Brand')->find($data['brand']);
            $o->setBrand($brand);
        }

        if (isset($data['class'])) {
            $o->setClass($data['class']);
        }

        if (isset($data['category'])) {
            $category = $em->getRepository('GistInventoryBundle:ProductCategory')->find($data['category']);
            $o->setCategory($category);
        }

        if (isset($data['item_type'])) {
            $type = $em->getRepository('GistInventoryBundle:ProductType')->find($data['item_type']);
            $o->setType($type);
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

        //COST
        $o->setCost($data['cost']);
        $o->setCostCurrency($data['cost_currency']);
        $o->setSRP($data['srp']);
        $o->setMinPrice($data['min_price']);

        //PERMITS
        $o->setFDAExpirationPrice($data['fda_exp_price']);
        $o->setPermitDateFrom(new DateTime($data['fda_date_from']));
        $o->setPermitDateTo(new DateTime($data['fda_date_to']));
        if($data['insurance_policy_document']!=0 && $data['insurance_policy_document'] != ""){
            $o->setScannedPermit($media->getUpload($data['insurance_policy_document']));
        }

        //DESCRIPTON
        $o->setDescription($data['desc_description']);
        $o->setIngredients($data['desc_ingredients']);
        $o->setDirections($data['desc_directions']);
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

    public function getTypeOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistInventoryBundle:ProductType',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

    //MIGRATE THIS
    public function uploadAction($id)
    {
        // TODO: confirm that product exists

        // handle dropzone
        $file = $this->getRequest()->files->get('file');
        if ($file->getError())
            return new Response('Failed');

        // let our gallery lib handle it
        $gallery = $this->getGallery($id);
        
        $gallery->addImage($file);

        return new Response('Success');
    }

    protected function getGallery($id)
    {
        return new Gallery(__DIR__ . '/../../../../web/uploads/dzones', $id);
    }
}
