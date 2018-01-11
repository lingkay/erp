<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\DamagedItems;
use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Entity\DamagedItemsEntry;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;


class DamagedItemsController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'gist_inv_damaged_items';
        $this->title = 'Damaged Items';

        $this->list_title = 'Damaged Items';
        $this->list_type = 'dynamic';
        $this->repo = "GistInventoryBundle:DamagedItems";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'gist_inv_damaged_items_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'GistInventoryBundle:DamagedItems:index.html.twig';


        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        return $this->render($twig_file, $params);
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistInventoryBundle:DamagedItems:action.html.twig',
            $params
        );
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new DamagedItems();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
//            $grid->newJoin('d_inv','destination_inv_account','getDestination'),
//            $grid->newJoin('s_inv','source_inv_account','getSource'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('ID','getID','id'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();

        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + array('00'=>'Damaged Items Warehouse') + $inv->getPOSLocationOptions();
        $params['item_opts'] = array('000'=>'-- Select Product --') + $inv->getProductOptionsTransfer();

        //CATEGORY
        $filter = array();
        $categories = $em
            ->getRepository('GistInventoryBundle:ProductCategory')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $cat_opts = array();
        $cat_opts[''] = 'All';
        foreach ($categories as $category)
            $cat_opts[$category->getID()] = $category->getName();

        $params['cat_opts'] = $cat_opts;

        $status_opts = array();
        $status_opts['requested'] = 'Requested';
        $status_opts['processed'] = 'Processed';
        $status_opts['delivered'] = 'Delivered';
        $status_opts['arrived'] = 'Arrived';

        $params['status_opts'] = $status_opts;

        return $params;
    }

    protected function add($obj)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        // validate
        $this->validate($data, 'add');

        // update db
        $this->update($obj, $data, true);

        $em->persist($obj);
        $em->flush();
        $this->hookPostSave($obj,true);

        // log
        $odata = $obj->toData();
        $this->logAdd($odata);
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        if ($is_new) {

            $o->setDescription($data['description']);
            // initialize entries
            $entries = array();

            $em->persist($o);
            $em->flush();


            foreach ($data['product_item_code'] as $index => $value)
            {
                $prod_item_code = $value;
                $qty = $data['quantity'][$index];

                // product
                $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                //from src
                $entry = new DamagedItemsEntry();
                $entry->setDamagedItems($o)
                    ->setProduct($prod)
                    ->setQuantity($qty);


                $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));

                echo $data['destination'][$index]."<br>";


                if ($data['destination'][$index] === '0') {
                    $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
                } elseif ($data['destination'][$index] === '00') {
                    echo 'here';
                    $wh_destination = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
                } else {
                    $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($data['destination'][$index]);
                }

                echo $wh_destination->getName();


                $entry->setSource($wh_src->getInventoryAccount());
                $entry->setDestination($wh_destination->getInventoryAccount());

                $entry->setStatus('requested');
                $entry->setRequestingUser($this->getUser());

                $em->persist($entry);
                $em->flush();

                $entries[] = $entry;
            }

//            die();

            return $entries;
        } else {


        }



    }

    public function statusUpdateAction($id, $status, $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('gist_configuration');
        if ($user != null) {
            $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$user));
        } else {
            $user = $this->getUser();
        }
        //$data = $this->getRequest()->request->all();
        // override for AJAX to ERP
        try
        {
            $damaged_item_entry = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findOneBy(array('id'=>$id));

            $damaged_item_entry->setStatus($status);

            if($status == 'requested') {
                $damaged_item_entry->setProcessedUser($user);
                $damaged_item_entry->setDateProcessed(new DateTime());
            } elseif ($status == 'delivered') {
                $damaged_item_entry->setDeliverUser($user);
                $damaged_item_entry->setDateDelivered(new DateTime());
            } elseif ($status == 'arrived') {
                $damaged_item_entry->setReceivingUser($user);
                $damaged_item_entry->setDateReceived(new DateTime());
            }

            $em->persist($damaged_item_entry);
            $em->flush();

            if ($user != null) {

                $list_opts[] = array(
                    'status'=>'success',
                    'parentID'=>$damaged_item_entry->getDamagedItems()->getID()
                );

                return new JsonResponse($list_opts);
            }

            $this->addFlash('success', 'Damaged items entry status updated successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(), array('id' => $damaged_item_entry->getDamagedItems()->getID())).$this->url_append);
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', 'Database error occured. Possible duplicate.');
            //return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error occured. Possible duplicate.');
            error_log($e->getMessage());
            //return $this->addError($obj);
        }
    }

    //    FOR PROD SEARCH MODAL AJAX
    protected function setupGridLoaderAjax()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        // loader
        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository($this->repo);

        // grid joins
        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);

        // grid columns
        $gcols = $this->getGridColumnsAjax();

        // add action column if it's dynamic
        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGridAjax'), false, false);

        // add columns
        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function getGridColumnsAjax()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Code', 'getItemCode', 'item_code','o', array($this,'formatItemCode')),
            $grid->newColumn('Barcode','getBarcode','barcode'),
            $grid->newColumn('Name', 'getName', 'name','o', array($this,'formatItemName')),
//            $grid->newColumn('Item Code', 'getItemCode', 'item_code','o', array($this,'formatDetails')),
        );
    }

    public function formatItemCode($val)
    {
        return '<input type="hidden" class="itemCode" value="'.$val.'">'.$val;
    }

    public function formatItemName($val)
    {
        return '<input type="hidden" class="itemName" value="'.$val.'">'.$val;
    }

    public function callbackGridAjax($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistInventoryBundle:DamagedItems:action_search.html.twig',
            $params
        );
    }

    public function gridSearchProductAction($category = null)
    {
        $this->hookPreAction();
        $this->repo = 'GistInventoryBundle:Product';
        $gloader = $this->setupGridLoaderAjax();
        $gloader->setRepository('GistInventoryBundle:Product');
        $gloader->setQBFilterGroup($this->filterProductSearch($category));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterProductSearch($category = null)
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $date = new DateTime();


//        $grid->setRepository('GistInventoryBundle:Product');

        if($category != null and $category != 'null') {
            $qry[] = "(o.category = '".$category."')";
        }
        else {
            $qry[] = "(o.id > 0)";
        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }
    //    END PROD SEARCH MODAL

    public function printPDFAction($id)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "GistInventoryBundle:DamagedItems:print.html.twig";

        $conf = $this->get('gist_configuration');

        //getOutputData
        $data = $this->getOutputData($id);

        $params['emp'] = null;
        $params['dept'] = null;


        $params['all'] = $data;
        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    private function getOutputData($id)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new DateTime();

        $query = $em    ->createQueryBuilder();
        $query          ->from('GistInventoryBundle:DamagedItems', 'o');

        $query      ->andwhere("o.id = '".$id."'");


        $data = $query          ->select('o')
            ->getQuery()
            ->getResult();

        return $data;
    }

    protected function hookPostSave($obj, $is_new = false)
    {

    }


    /**
     *
     * Function for POS to fetch stock transfer records
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getSentFromPOSAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $stock_transfer_entries = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findBy(array('source_inv_account'=>$pos_location->getInventoryAccount()->getID()));


        $list_opts = [];
        foreach ($stock_transfer_entries as $p) {
            $list_opts[] = array(
                'id'=>$p->getDamagedItems()->getID(),
            );

        }

        $list_opts = array_map("unserialize", array_unique(array_map("serialize", $list_opts)));
        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to fetch stock transfer records
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getSentToPOSAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $stock_transfer_entries = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findBy(array('destination_inv_account'=>$pos_location->getInventoryAccount()->getID()));

        $list_opts = [];
        foreach ($stock_transfer_entries as $p) {
            $list_opts[] = array(
                'id'=>$p->getDamagedItems()->getID(),
            );

        }

        $list_opts = array_map("unserialize", array_unique(array_map("serialize", $list_opts)));
        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to fetch location options
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getLocationOptionsAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();

        $inv = $this->get('gist_inventory');
        $list_opts = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + $inv->getPOSLocationOptions();

        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to fetch prod cat options
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getProductCategoryOptionsAction()
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        //CATEGORY
        $filter = array();
        $categories = $em
            ->getRepository('GistInventoryBundle:ProductCategory')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $cat_opts = array();
        $cat_opts[''] = 'All';
        foreach ($categories as $category)
            $cat_opts[$category->getID()] = $category->getName();

        return new JsonResponse($cat_opts);
    }

    /**
     *
     * Function for POS to fetch stock transfer form data
     *
     * @param $id
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getPOSFormDataAction($id, $pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $pos_iacc_id = $pos_location->getInventoryAccount()->getID();
        $list_opts = [];

//        if ($st->getSource()->getID() == $pos_iacc_id || $st->getDestination()->getID() == $pos_iacc_id) {
        $list_opts[] = array(
            'id'=>$st->getID(),
            'description'=> $st->getDescription(),
            'pos_iacc_id' => $pos_iacc_id,
            'invalid'=>'false',
        );
//        } else {
//            $list_opts[] = array(
//                'id'=>0,
//                'invalid'=>'true',
//            );
//        }



        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to fetch stock transfer form data
     *
     * @param $id
     * @return JsonResponse
     * @internal param $pos_loc_id
     */
    public function getPOSFormDataEntriesAction($id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findBy(array('damaged_items'=>$id));


        $list_opts = [];
        foreach ($st as $p) {
            $list_opts[] = array(
                'id'=>$p->getID(),
                'item_code'=>$p->getProduct()->getItemCode(),
                'product_name'=> $p->getProduct()->getName(),
                'quantity'=> $p->getQuantity(),
                'source'=> $p->getSource()->getName(),
                'destination'=> $p->getDestination()->getName(),
                'source_id'=> $p->getSource()->getID(),
                'destination_id'=> $p->getDestination()->getID(),
//                'date_create'=> $p->getDateCreate()->format('y-m-d H:i:s'),
                'status'=> $p->getStatus(),
                'statusFMTD'=> $p->getStatusFMTD(),
                'user_create' => $p->getRequestingUser()->getDisplayName(),
                'user_processed' => ($p->getProcessedUser() == null ? '-' : $p->getProcessedUser()->getDisplayName()),
                'user_delivered' => ($p->getDeliverUser() == null ? '-' : $p->getDeliverUser()->getDisplayName()),
                'user_received' => ($p->getReceivingUser() == null ? '-' : $p->getReceivingUser()->getDisplayName()),
                'date_processed' => ($p->getDateProcessed() == null ? '' : $p->getDateProcessed()->format('y-m-d H:i:s')),
                'date_delivered' => ($p->getDateDelivered() == null ? '' : $p->getDateDelivered()->format('y-m-d H:i:s')),
                'date_received' => ($p->getDateReceived() == null ? '' : $p->getDateReceived()->format('y-m-d H:i:s')),
            );

        }

        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to update stock transfer status
     *
     * @param $id
     * @return JsonResponse
     * @internal param $pos_loc_id
     */
    public function updatePOSStockTransferAction($id, $userId, $status)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$userId));

        $st->setStatus($status);

        if($status == 'processed') {
            $st->setProcessedUser($user);
            $st->setDateProcessed(new DateTime());

        } elseif ($status == 'delivered') {
            $st->setDeliverUser($user);
            $st->setDateDelivered(new DateTime());
        } elseif ($status == 'arrived') {
            $st->setReceivingUser($user);
            $st->setDateReceived(new DateTime());
        } else {
            // for overwrite scenario
            $list_opts[] = array(
                'status'=>'failed'
            );

            return new JsonResponse($list_opts);
        }

        $em->persist($st);
        $em->flush();

        $list_opts[] = array(
            'status'=>'success'
        );

        return new JsonResponse($list_opts);
    }

    //{src}/{dest}/{user}/{description}/{entries}

    /**
     *
     * Function for POS to add stock transfer
     *
     * @param $src
     * @param $dest
     * @param $user
     * @param $description
     * @param $entries
     * @return JsonResponse
     * @internal param $pos_loc_id
     */
    public function addPOSDamagedItemsAction($src, $user, $description, $entries)
    {
        header("Access-Control-Allow-Origin: *");

        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        //$st = $em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$user));
        parse_str($entries, $entriesParsed);

        $st = new DamagedItems();
        $st->setDescription($description);
        // initialize entries
        $entries = array();

        $em->persist($st);
        $em->flush();

        foreach ($entriesParsed as $e) {
            $prod_item_code = $e['code'];
            $qty = $e['quantity'];
            $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            //from src
            $entry = new DamagedItemsEntry();
            $entry->setDamagedItems($st)
                ->setProduct($prod)
                ->setQuantity($qty);

            // warehouse - source
            if ($src == '0') {
                $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } elseif ($src == '00') {
                $wh_src = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
            } else {
                $wh_src = $em->getRepository('GistLocationBundle:POSLocations')->find($src);
            }

            // warehouse - destination
            if ($e['destination'] === '0') {
                $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } elseif ($e['destination'] === '00') {
                $wh_destination = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
            } else {
                $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($e['destination']);
            }

            $entry->setSource($wh_src->getInventoryAccount());
            $entry->setDestination($wh_destination->getInventoryAccount());

            $entry->setStatus('requested');
            $entry->setRequestingUser($user);

            $em->persist($entry);
            $em->flush();

            $entries[] = $entry;
        }

        //die();

        //return $entries;

        $list_opts[] = array(
            'status'=>'success',
            'id'=>$st->getID()
        );

        return new JsonResponse($list_opts);
    }
}
