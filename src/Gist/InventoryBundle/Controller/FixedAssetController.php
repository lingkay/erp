<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Entity\ServiceTask;
use Gist\ValidationException;
use Gist\InventoryBundle\Model\Gallery;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\CoreBundle\Template\Controller\TrackUpdate;
use Gist\InventoryBundle\Entity\SupplierProduct;
use Gist\CoreBundle\Template\Controller\HasGeneratedID;
use Gist\CoreBundle\Template\Controller\HasName;
use Symfony\Component\HttpFoundation\Response;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\StockHistory;
use Doctrine\ORM\EntityManager;
use DateTime;
use SplFileObject;
use LimitIterator;

class FixedAssetController extends CrudController
{
    use TrackCreate;
    use TrackUpdate;
    use HasGeneratedID;
    use HasName;

    public function __construct()
    {
        $this->route_prefix = 'cat_inv_fixed_asset';
        $this->title = 'Supplies and Equipments';

        $this->list_title = 'Supplies and Equipments';
        $this->list_type = 'dynamic';
        $this->repo = 'GistInventoryBundle:Product';
    }

    public function indexAction()
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'FareastInventoryBundle:FixedAsset:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $this->csv = 'cat_inv_fixed_asset_export';
        $params['csv'] = $this->csv;

        return $this->render($twig_file, $params);
    }

    protected function setupGridLoader()
    {
        $grid = $this->get('gist_grid');

        $loader = parent::setupGridLoader();

        $fg = $grid->newFilterGroup();
        $fg->where('o.type_id = :type_id')
            ->setParameter('type_id', Product::TYPE_FIXED_ASSET);

        $loader->setQBFilterGroup($fg);

        return $loader;
    }


    // TODO : Find delete error
    public function addSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->add();
        $obj->setUserCreate($this->getUser());
        try
        {
            $this->persist($obj);

            $this->addFlash('success', $this->title . ' added successfully.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            print_r($e->getMessage());
            $this->addFlash('error', 'Database error encountered. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }
    }

    protected function newBaseClass()
    {
        return new Product();
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
            return '';
        return $obj->getSKU();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('pg', 'prodgroup', 'getProductGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('SKU', 'getSKU', 'sku'),
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Unit', 'getUnitOfMeasure', 'uom'),
            $grid->newColumn('Group', 'getName', 'name', 'pg'),
        );
    }

    protected function getFieldOptions($repo)
    {
        // brand
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository('GistInventoryBundle:' . $repo)->findAll();
        $opts = array();
        foreach ($objects as $o)
            $opts[$o->getId()] = $o->getName();

        return $opts;
    }

    protected function padFormParams(&$params, $prod = null)
    {
         $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $params['pg_opts'] = $inv->getProductGroupOptions();
        $params['brand_opts'] = $this->getFieldOptions('Brand');

        // images
        if ($prod != null) 
        {
            if ($prod->getID())
            {
                $gallery = $this->getGallery($prod->getID());
                $images = $gallery->getImages();
                $params['images'] = $images;
            }

        }

         $params['wh_opts'] = $inv->getWarehouseOptions();
        $params['supplier_opts'] = $inv->getSupplierOptions();
        $params['suppliers'] = $em->getRepository('GistInventoryBundle:SupplierProduct')->findBy(array('product' => $prod));
        return $params;
    }

    protected function validate($data, $type)
    {
        // SKU validation check
        if (empty($data['sku']))
            throw new ValidationException('Cannot leave SKU blank');

        // validate name
        if (empty($data['name']))
            throw new ValidationException('Cannot leave name blank');
    }

    protected function update($o, $data, $is_new = false)
    {

        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        
        $o->setName($data['name']);

        // unit of measure
        if (!empty($data['uom']))
            $o->setUnitOfMeasure($data['uom']);
        else
            $o->setUnitOfMeasure('');


        // product group
        $pg = $inv->findProductGroup($data['prodgroup_id']);
        if ($pg == null)
            throw new ValidationException('Could not find product group specified.');
        $o->setProductGroup($pg);

        // product brand
        $pb = $inv->findBrand($data['brand_id']);
        if ($pb != null)
            $o->setBrand($pb);        


        // sku check
        if ($o->getSKU() != $data['sku'])
        {
            $em = $this->getDoctrine()->getManager();
            $dupe = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('sku' => $data['sku']));
            if ($dupe != null)
                throw new ValidationException('Product with SKU ' . $data['sku'] . ' already exists.');
            $o->setSKU($data['sku']);
        }

        //refactor, update instead of remove
        $suppliers = $em->getRepository('GistInventoryBundle:SupplierProduct')->findBy(array('product' => $o));
        foreach ($suppliers as $supplier) {
            $em->remove($supplier);
        }

        //avoid duplicates
        if (isset($data['supplier_id'])) {
            if (count($data['supplier_id']) > 0) {
                foreach ($data['supplier_id'] as $index => $supp) {
                    $supplier = $em->getRepository('ZuluPurchasingBundle:Supplier')->findOneBy(array('id' => $supp));
                    $supplier_product = new SupplierProduct();
                    $supplier_product->setSupplier($supplier)
                                    ->setPrice($data['supplier_price'][$index])
                                    ->setProduct($o);
                    $em->persist($supplier_product);
                }
            }
        }


        $this->updateTrackCreate($o, $data, $is_new);
        $this->updateTrackUpdate($o, $data, $is_new);
        $this->updateHasGeneratedID($o, $data, $is_new);
        $this->updateHasName($o, $data, $is_new);

        $o->setTypeID(Product::TYPE_FIXED_ASSET);


    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'sku' => $o->getSKU(),
            'name' => $o->getName(),
            'uom' => $o->getUnitOfMeasure(),
            'flag_service' => $o->isService(),
            'flag_sale' => $o->canSell(),
            'flag_purchase' => $o->canPurchase(),
            'price_sale' => $o->getPriceSale(),
            'price_purchase' => $o->getPricePurchase(),
            'prodgroup_id' => $o->getProductGroup()->getID(),
        );

        return $data;
    }

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

    protected function getAdjustmentAccount()
    {
        $em = $this->getDoctrine()->getManager();

        $config = $this->get('gist_configuration');
        $adj_warehouse_id = $config->get('gist_warehouse_stock_adjustment');
        $adj_warehouse = $em->getRepository('GistInventoryBundle:Warehouse')->find($adj_warehouse_id);

        $acc = $em->getRepository('GistInventoryBundle:Account')->find($adj_warehouse->getInventoryAccount()->getID());

        return $acc;
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $data = $this->getRequest()->request->all();
        $config = $this->get('gist_configuration');
        if ($is_new) {
            $main_warehouse = $inv->findWarehouse($data['warehouse']);
            $adj_warehouse = $inv->findWarehouse($config->get('gist_warehouse_stock_adjustment'));
            $wh_acc = $main_warehouse->getInventoryAccount();
            $adj_acc = $this->getAdjustmentAccount();
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

    protected function getGallery($id)
    {
        return new Gallery(__DIR__ . '/../../../../web/uploads/images', $id);
    }

    public function ajaxAddAction()
    {
        $data = $this->getRequest()->query->all();
        $this->hookPreAction();
        $data = array();
        try
        {
            $em = $this->getDoctrine()->getManager();
            $data = $this->getRequest()->request->all();

            $obj = $this->newBaseClass();

            $this->update($obj, $data, true);

            $em->persist($obj);
            $em->flush();

            $buildData = $this->buildData($obj);

            $data = array(
                'status' => array('success' => true, 'message' => $this->getObjectLabel($obj) . ' added successfully.'),
                'data' => $buildData,
            );
        }
        catch (ValidationException $e)
        {
            $data = array(
                'status' => array('success' => false, 'message' => $e->getMessage()),
                'data' => null,
            );
        }
        catch (DBALException $e)
        {
            $data = array(
                'status' => array('success' => false, 'message' => 'Database error encountered. Possible duplicate.'),
                'data' => null,
            );
        }
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function findBy($filter = array())
    {
        $this->hookPreAction();

        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->findBy($filter);

        $data = array();
        foreach($obj as $val)
        {
            array_push($data, $this->buildData($val));
        }

        return $data;
    }

    public function ajaxGetByAction($id)
    {
        //$prodgroup = $this->get('gist_configuration');
        //$data = $this->findBy(array('prodgroup' => $prodgroup->get('gist_product_group_default')));
        $inv = $this->get('gist_inventory');
        $data = $inv->findProduct($id)->toData();
        // setup json response
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function getStockReport()
    {
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery('select p.code, p.name, p.uom, j.name as j_name from Gist\InventoryBundle\Entity\Product p join p.prodgroup j order by p.name asc');              
        return $query->getResult();
    }

    public function deleteImageAction($id, $loop)
    {
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('GistInventoryBundle:Product')->find($id);
        $gallery = $this->getGallery($id);
        $images = $gallery->getImages();
        unlink("uploads/images/".$images[$loop - 1]);
        $em->flush();
        $this->addFlash('success','Image has been deleted');
  
        $params["id"] = $id;    
        return $this->redirect($this->generateUrl('cat_inv_prod_edit_form', $params));
    }

    public function headers()
    {
        // csv headers
        $headers = [
            'SKU',
            'Name',            
            'Unit',
            // 'Qty',
            'Group',
            'Unit Price',
            // 'Total Price',
        ];
        return $headers;
    }
    
    public function csvAction($category = null, $item = null )
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        // $config = $this->get('gist_configuration');
        // $main_warehouse = $inv->findWarehouse($config->get('gist_warehouse_main'));

        // $main_warehouse =  $main_warehouse->getInventoryAccount();
        // $whid = $main_warehouse->getID();
        // $variants = $this->filterVariant($item);

        // $report = new reportManager($this->getDoctrine()->getManager());
        // $data = $report->getStockData($category, $item, $whid, $variants);

        $data = $em->getRepository('GistInventoryBundle:Product')->findBy(array('type_id'=>Product::TYPE_FIXED_ASSET));
        // filename generate
        $date = new DateTime();
        $filename = $date->format('Ymdis') . '.csv';

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

         $i=0;
        foreach ($data as $d)
        {
            // build data
            $arr_data = [
                $d->getSKU(),
                $d->getName(),
                $d->getUnitOfMeasure(),
                // $d->getQuantity(),
                $d->getProductGroup()->getName(),
                $d->getPriceSale(),
                // $d->getTotalPrice()

            ];

            $i++;
            // output csv
            fputcsv($file, $arr_data);
        }


        fclose($file);


        // csv header
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }

    public function importCsvAction()
   {
       $em = $this->getDoctrine()->getManager();
       $files = $this->getRequest()->files->get('file');
       foreach ($files as $file) {
           try
           {
               $rows = $this->parseCSV($file);
               
              
               foreach ($rows as $row)
               {
                     $product_group = $em->getRepository('GistInventoryBundle:ProductGroup')
                     ->findOneBy(
                       array('name'=>$row['product_group'])
                     );

                     //$inv = $this->get('gist_inventory');
                     //$pb = $inv->findBrand(1);

                     $brand = $em->getRepository('GistInventoryBundle:Brand')
                     ->findOneBy(
                       array('name'=>$row['brand'])
                     );
                    $fixedasset = new Product();

                    $fixedasset -> setSKU($row['sku'])
                                -> setName($row['name'])
                                -> setUnitOfMeasure($row['uom'])
                                -> setProductGroup($product_group)
                                -> setBrand($brand)
                                -> setDateCreate(new DateTime())
                                ->setTypeID(Product::TYPE_FIXED_ASSET);               
                                  
                    $em->persist($fixedasset);  
   
                    $this->updateTrackCreate($fixedasset, $row, true);
                    $this->updateTrackUpdate($fixedasset, $row, true);

                     

                    
               }
               $em->flush();
               
               $this->addFlash('success', 'Import of CSV successful.');
           }
           catch (ValidationException $e)
           {
               // TODO: return error
               $this->addFlash('error', $e->getMessage());
           }
           catch (DBALException $e)
           {
               // TODO: return error
               $this->addFlash('error', $e->getMessage());
           }
       }

       return $this->redirect($this->getRequest()->headers->get('referer'));
   }

   protected function parseCSV($files)
   {
       $em = $this->getDoctrine()->getManager();
       $data = new SplFileObject($files);
       $data->setFlags(SplFileObject::READ_CSV);
       $fixedassets = new LimitIterator($data, 1);
       $rows = [];
       foreach ($fixedassets as $fixedasset) {
           if(array_key_exists(1, $fixedasset))
           {
               $rows[] = array(
                   'sku' => $fixedasset[0],
                   'name' => $fixedasset[1],
                   'uom' => $fixedasset[2],
                   'product_group' => $fixedasset[3],
                   'brand' => $fixedasset[4],
                   );
           }
           else {
               break;
           }
       }

       return $rows;
   }


}



