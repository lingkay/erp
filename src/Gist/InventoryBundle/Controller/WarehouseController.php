<?php

namespace Gist\InventoryBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Warehouse;
use Gist\InventoryBundle\Template\Controller\HasInventoryAccount;
use Gist\ValidationException;
use Gist\InventoryBundle\Entity\Account;
use Gist\InventoryBundle\Entity\Stock;

class WarehouseController extends CrudController
{
    use HasInventoryAccount;
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_wh';
        $this->title = 'Warehouse';

        $this->list_title = 'Warehouses';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Warehouse();
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
            $grid->newColumn('Address', 'getAddress', 'address'),
            $grid->newColumn('Contact Number', 'getPhone', 'phone'),
            $grid->newColumn('Type', 'getType', 'type'),
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        if (empty($data['name']))
            throw new ValidationException('Cannot leave name blank');
            
        $o->setName($data['name'])
            ->setType($data['type_id']);

        if ($data['type_id'] == 'physical') 
        {
            $o->setPhone($data['contact_num'])
            ->setAddress($data['address']);
        }
        
        if ($is_new == true)
        {
            $o->setUserCreate($this->getUser());
        }
        $this->updateHasInventoryAccount($o, $data, $is_new);

    }

    protected function createInventoryAccount($o, $data)
    {
        $allow = false;

        $account = new Account();
        $account->setName($data['name'])
            ->setUserCreate($this->getUser())
            ->setAllowNegative($allow);

        return $account;
    }

    protected function padFormParams(&$params, $o = null)
    {
        $params['wh_type_opts'] = array(
            'physical' => 'Physical',
            'virtual' => 'Virtual',
            'tank' => 'Tank',
            'adjustment' => 'Adjustment'
        );

        if ($o->getID())
            $params['stock_cols'] = $this->getStockColumns();

        return $params;
    }

    protected function getStockColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Code', 'getCode', 'code', 'p'),
            $grid->newColumn('Name', 'getName', 'name', 'p'),
            $grid->newColumn('Quantity', 'getQuantity', 'quantity'),
        );
    }

    protected function setupStockGrid($id)
    {
        $grid = $this->get('gist_grid');
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        // limit to this warehouse's stock
        $fg = $grid->newFilterGroup();
        $fg->where('w.id = ?1')
            ->setParameter(1, $id);

        // setup grid
        $gl = $grid->newLoader();
        $gl->processParams($data)
            ->setRepository('GistInventoryBundle:Stock')
            ->addJoin($grid->newJoin('p', 'product', 'getProduct'))
            ->addJoin($grid->newJoin('w', 'warehouse', 'getWarehouse'))
            ->enableCountFilter()
            ->setQBFilterGroup($fg);

        // columns
        $stock_cols = $this->getStockColumns();
        foreach ($stock_cols as $col)
            $gl->addColumn($col);

        return $gl;
    }

    public function stockGridAction($id)
    {
        $gl = $this->setupStockGrid($id);
        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'name' => $o->getName(),
        );

        return $data;
    }

    protected function hookPostSave($obj, $is_new = false)
    {

        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $wh_acc = $obj->getInventoryAccount();
        $existing_stocks = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$wh_acc->getID()));
        $existingProductsArray = [];
        foreach ($existing_stocks as $es) {
            array_push($existingProductsArray, $es->getProduct()->getID());
        }
        //create ZERO stock entries for products
        //if ($obj->getBrand() != '') {
            //$brands = explode(',', $obj->getBrand());
            //foreach ($brands as $brand) {

                //$brand_object = $em->getRepository('GistInventoryBundle:Brand')->findOneBy(array('name'=>$brand));


                //$brand_id = $brand_object->getID();
                $products = $em->getRepository('GistInventoryBundle:Product')->findAll();

                if ($products) {
                    if (count($existing_stocks) > 0) {
                        //IF LOCATIONS IACC HAS AT LEAST ONE STOCK
                        //CREATE ZERO ENTRY ONLY FOR ITEMS WITHOUT EXISTING RECORDS
                        foreach ($products as $product_object) {
                            if (!in_array($product_object->getID(), $existingProductsArray)) {
                                //create ZERO entry
                                $stock_entry = new Stock($wh_acc, $product_object);
                                $stock_entry->setInventoryAccount($wh_acc);
                                $stock_entry->setProduct($product_object);
                                $stock_entry->setQuantity(0);
                                $em->persist($stock_entry);
                                $em->flush();
                            }
                        }
                    } else {
                        //IF LOCATIONS IACC HAS NO PRODUCTS AT ALL
                        foreach ($products as $product_object) {
                            //create ZERO entry
                            $stock_entry = new Stock($wh_acc, $product_object);
                            $stock_entry->setInventoryAccount($wh_acc);
                            $stock_entry->setProduct($product_object);
                            $stock_entry->setQuantity(0);
                            $em->persist($stock_entry);
                            $em->flush();
                        }
                    }
                    //IF BRAND PRODUCTS FOUND
                }

                //END BRANDS LOOP
            //}
        //}

        //CREATE ENTRIES FOR FIXED ASSETS
        $fixedAssetsBrandID = $config->get('gist_fixed_asset_brand');
        if ($fixedAssetsBrandID != '') {
            $products = $em->getRepository('GistInventoryBundle:Product')->findBy(array('brand'=>$fixedAssetsBrandID));
            if ($products) {
                foreach ($products as $product_object) {
                    if (!in_array($product_object->getID(), $existingProductsArray)) {
                        //create ZERO entry
                        $stock_entry = new Stock($wh_acc, $product_object);
                        $stock_entry->setInventoryAccount($wh_acc);
                        $stock_entry->setProduct($product_object);
                        $stock_entry->setQuantity(0);
                        $em->persist($stock_entry);
                        $em->flush();
                    }
                }
            }
        }
    }
}

