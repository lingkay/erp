<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Model\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\ValidationException;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;

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

        $inv = $this->get('gist_inventory');
        //$params['wh_opts'] = $inv->getWarehouseOptions();

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

//        if (isset($data['min_stock'])) {
//            $o->setMinStock($data['min_stock']);
//        } else {
//            $o->setMinStock(0);
//        }
//
//        if (isset($data['max_stock'])) {
//            $o->setMaxStock($data['max_stock']);
//        } else {
//            $o->setMaxStock(0);
//        }

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

        if (isset($data['cost_currency'])) {
            $o->setCostCurrency($data['cost_currency']);
        }

        $o->setSRP($data['srp']);
        $o->setMinPrice($data['min_price']);

        //PERMITS
        if (isset($data['fda_exp_price'])) {
            $o->setFDAExpirationPrice($data['fda_exp_price']);
        }

        if (isset($data['fda_date_from'])) {
            $o->setPermitDateFrom(new DateTime($data['fda_date_from']));
        }

        if (isset($data['fda_date_to'])) {
            $o->setPermitDateTo(new DateTime($data['fda_date_to']));
        }



        if($data['insurance_policy_document']!=0 && $data['insurance_policy_document'] != ""){
            $o->setScannedPermit($media->getUpload($data['insurance_policy_document']));
        }

        //DESCRIPTON
        $o->setDescription($data['desc_description']);
        $o->setIngredients($data['desc_ingredients']);
        $o->setDirections($data['desc_directions']);
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $data = $this->getRequest()->request->all();
        $config = $this->get('gist_configuration');
        if (isset($data['qty'])) {
            if ($data['qty'] != '') {
                $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
                $adj_warehouse = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
                $wh_acc = $main_warehouse->getInventoryAccount();
                $adj_acc = $adj_warehouse->getInventoryAccount();
                $new_qty = $data['qty'];
                $old_qty = 0;

                // setup transaction
                $trans = new Transaction();
                $trans->setUserCreate($this->getUser())
                    ->setDescription('Initial balance');

                // add entries
                // entry for warehouse
                $wh_entry = new Entry();
                $wh_entry->setInventoryAccount($wh_acc)
                    ->setProduct($obj);

                // entry for adjustment
                $adj_entry = new Entry();
                $adj_entry->setInventoryAccount($adj_acc)
                    ->setProduct($obj);

                // check if debit or credit
                if ($new_qty > $old_qty)
                {
                    $qty = $new_qty - $old_qty;
                    $wh_entry->setDebit($qty);
                    $adj_entry->setCredit($qty);
                }
                else
                {
                    $qty = $old_qty - $new_qty;
                    $wh_entry->setCredit($qty);
                    $adj_entry->setDebit($qty);
                }
                $entries[] = $wh_entry;
                $entries[] = $adj_entry;

                foreach ($entries as $ent)
                    $trans->addEntry($ent);

                $inv->persistTransaction($trans);
                $em->flush();
            }
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
