<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\StockTransfer;
use Gist\InventoryBundle\Entity\StockTransferEntry;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use DateTime;


class StockTransferController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'gist_inv_stock_transfer';
        $this->title = 'Stock Transfer';

        $this->list_title = 'Stock Transfer';
        $this->list_type = 'dynamic';
        $this->repo = "GistInventoryBundle:StockTransfer";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'gist_inv_stock_transfer_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'GistInventoryBundle:StockTransfer:index.html.twig';


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
            'GistInventoryBundle:StockTransfer:action.html.twig',
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
        return new StockTransfer();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('d_inv','destination_inv_account','getDestination'),
            $grid->newJoin('s_inv','source_inv_account','getSource'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('ID','getID','id'),
            $grid->newColumn('Status','getStatusFMTD','status'),
            $grid->newColumn('Source','getName','name','s_inv'),
            $grid->newColumn('Destination','getName','name','d_inv'),
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_user');
        $params['user_opts'] = $um->getUserFullNameOptions();
        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + $inv->getPOSLocationOptions();
        $params['item_opts'] = array('000'=>'-- Select Product --') + $inv->getProductOptionsTransfer();

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

        return $params;
    }

    protected function add($obj)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        $this->validate($data, 'add');

        $this->update($obj, $data, true);

        $em->persist($obj);
        $em->flush();
        $this->hookPostSave($obj,true);

        $odata = $obj->toData();
        $this->logAdd($odata);
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        if ($is_new || !isset($data['status'])) {

            // initialize entries
            $entries = array();

            // warehouse
            if ($data['source'] == 0) {
                $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } else {
                $wh_src = $em->getRepository('GistLocationBundle:POSLocations')->find($data['source']);
            }

            if ($data['destination'] == 0) {
                $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } else {
                $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($data['destination']);
            }

            if ($is_new) {
                $o->setStatus('requested');
                $o->setRequestingUser($this->getUser());

                $o->setDescription($data['description']);
                $o->setSource($wh_src->getInventoryAccount());
                $o->setDestination($wh_destination->getInventoryAccount());

                $em->persist($o);
                $em->flush();
            }



            if (!$is_new) {
                $existingEntries = $em->getRepository('GistInventoryBundle:StockTransferEntry')->findBy(array('stock_transfer'=>$o->getID()));
                foreach ($existingEntries as $ee) {
                    $em->remove($ee);
                }
            }

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
                $entry = new StockTransferEntry();
                $entry->setStockTransfer($o)
                    ->setProduct($prod)
                    ->setQuantity($qty);

                $em->persist($entry);
                $em->flush();

                $em->persist($entry);
                $em->flush();

                $entries[] = $entry;
            }

            return $entries;
        } else {
            $o->setStatus($data['status']);

            if($data['status'] == 'processed') {
                $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$data['selected_user']));
                $o->setProcessedUser($user);
                $o->setDateProcessed(new DateTime());

            } elseif ($data['status'] == 'delivered') {
                $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$data['selected_user']));
                $o->setDeliverUser($user);
                $o->setDateDelivered(new DateTime());
            } elseif ($data['status'] == 'arrived') {
                $o->setReceivingUser($this->getUser());
                $o->setDateReceived(new DateTime());

                $entries = array();

                foreach ($data['st_entry'] as $index => $value)
                {
                    $entry_id = $value;
                    $qty = $data['received_quantity'][$index];

                    // entry
                    $entry = $em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id'=>$entry_id));
                    $entry->setReceivedQuantity($qty);

                    $em->persist($entry);
                    $em->flush();

                    $entries[] = $entry;
                }

                return $entries;
            }
        }
    }

    public function printPDFAction($id)
    {
        $twig = "GistInventoryBundle:StockTransfer:print.html.twig";

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

        $query = $em->createQueryBuilder();
        $query->from('GistInventoryBundle:StockTransfer', 'o');
        $query->andwhere("o.id = '".$id."'");

        $data = $query->select('o')
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
        $stock_transfers = $em->getRepository('GistInventoryBundle:StockTransfer')->findBy(array('source_inv_account'=>$pos_location->getInventoryAccount()->getID()));

        $list_opts = [];
        foreach ($stock_transfers as $p) {
            $list_opts[] = array(
                'id'=>$p->getID(),
                'source'=> $p->getSource()->getName(),
                'destination'=> $p->getDestination()->getName(),
                'date_create'=> $p->getDateCreateFormatted(),
                'status'=> ucfirst($p->getStatus()),
            );
        }

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
        $stock_transfers = $em->getRepository('GistInventoryBundle:StockTransfer')->findBy(array('destination_inv_account'=>$pos_location->getInventoryAccount()->getID()));

        $list_opts = [];
        foreach ($stock_transfers as $p) {
            $list_opts[] = array(
                'id'=>$p->getID(),
                'source'=> $p->getSource()->getName(),
                'destination'=> $p->getDestination()->getName(),
                'date_create'=> $p->getDateCreateFormatted(),
                'status'=> ucfirst($p->getStatus()),
            );
        }

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
        $list_opts = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptions();
        unset($list_opts[$pos_loc_id]);
        return new JsonResponse($list_opts);
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
        $st = $em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $pos_iacc_id = $pos_location->getInventoryAccount()->getID();
        $list_opts = [];

        if ($st->getSource()->getID() == $pos_iacc_id || $st->getDestination()->getID() == $pos_iacc_id) {
            $list_opts[] = array(
                'id'=>$st->getID(),
                'source'=> $st->getSource()->getName(),
                'source_id'=> $st->getSource()->getID(),
                'destination'=> $st->getDestination()->getName(),
                'destination_id'=> $st->getDestination()->getID(),
                'pos_iacc_id' => $pos_iacc_id,
                'date_create'=> $st->getDateCreate()->format('y-m-d H:i:s'),
                'status'=> $st->getStatus(),
                'description'=> $st->getDescription(),
                'user_create' => $st->getRequestingUser()->getDisplayName(),
                'user_processed' => ($st->getProcessedUser() == null ? '-' : $st->getProcessedUser()->getDisplayName()),
                'user_delivered' => ($st->getDeliverUser() == null ? '-' : $st->getDeliverUser()->getDisplayName()),
                'user_received' => ($st->getReceivingUser() == null ? '-' : $st->getReceivingUser()->getDisplayName()),
                'date_processed' => ($st->getDateProcessed() == null ? '' : $st->getDateProcessed()->format('y-m-d H:i:s')),
                'date_delivered' => ($st->getDateDelivered() == null ? '' : $st->getDateDelivered()->format('y-m-d H:i:s')),
                'date_received' => ($st->getDateReceived() == null ? '' : $st->getDateReceived()->format('y-m-d H:i:s')),
                'invalid'=>'false',
            );
        } else {
            $list_opts[] = array(
                'id'=>0,
                'source'=> 0,
                'destination'=> 0,
                'date_create'=> 0,
                'status'=> 0,
                'description'=> 0,
                'user_create' => 0,
                'user_processed' => 0,
                'user_delivered' => 0,
                'user_received' => 0,
                'date_processed' => 0,
                'date_delivered' => 0,
                'date_received' => 0,
                'invalid'=>'true',
            );
        }

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
        $st = $em->getRepository('GistInventoryBundle:StockTransferEntry')->findBy(array('stock_transfer'=>$id));

        $list_opts = [];
        foreach ($st as $p) {
            $list_opts[] = array(
                'id'=>$p->getID(),
                'item_code'=>$p->getProduct()->getItemCode(),
                'product_name'=> $p->getProduct()->getName(),
                'quantity'=> $p->getQuantity(),
                'received_quantity'=> $p->getReceivedQuantity(),
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
    public function updatePOSStockTransferAction($id, $userId, $status, $entries)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
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
            parse_str($entries, $entriesParsed);


            foreach ($entriesParsed as $e) {
                if (isset($e['st_entry'])) {
                    $entry_id = $e['st_entry'];
                    $qty = $e['received_quantity'];

                    // entry
                    $entry = $em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id'=>$entry_id));
                    $entry->setReceivedQuantity($qty);

                    $em->persist($entry);
                    $em->flush();

                    //$entries[] = $entry;
                }
            }

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

    /**
     *
     * Function for POS to add stock transfer
     *
     * @param $src
     * @param $dest
     * @param $user
     * @param $description
     * @param $entries
     * @param $status
     * @param $id
     * @return JsonResponse
     * @internal param $pos_loc_id
     */
    public function addPOSStockTransferAction($src, $dest, $user, $description, $entries, $status, $id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$user));

        parse_str($entries, $entriesParsed);

        if ($status != 'to_update') {
            $st = new StockTransfer();
            $st->setStatus('requested');
            $st->setRequestingUser($user);

            // warehouse
            if ($src == '0') {
                $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } else {
                $wh_src = $em->getRepository('GistLocationBundle:POSLocations')->find($src);
            }

            if ($dest == '0') {
                $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } else {
                $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($dest);
            }

            $st->setDescription($description);
            $st->setSource($wh_src->getInventoryAccount());
            $st->setDestination($wh_destination->getInventoryAccount());

            $em->persist($st);
            $em->flush();

            foreach ($entriesParsed as $e) {
                $prod_item_code = $e['code'];
                $qty = $e['quantity'];
                $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code' => $prod_item_code));
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                //from src
                $entry = new StockTransferEntry();
                $entry->setStockTransfer($st)
                    ->setProduct($prod)
                    ->setQuantity($qty);

                $em->persist($entry);
                $em->flush();


                $em->persist($entry);
                $em->flush();

                //$entries[] = $entry;
            }

            $em->persist($st);
            $em->flush();

            $list_opts[] = array(
                'status'=>'success',
                'id'=>$st->getID()
            );
        } else {
            $transferStock = $em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
            $existingEntries = $em->getRepository('GistInventoryBundle:StockTransferEntry')->findBy(array('stock_transfer'=>$transferStock->getID()));

            foreach ($existingEntries as $ee) {
                $em->remove($ee);
            }

            $em->flush();

//            var_dump($entriesParsed);
//            die();

            foreach ($entriesParsed as $e) {
                $prod_item_code = $e['code'];
                $qty = $e['quantity'];
                $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code' => $prod_item_code));
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                //from src
                $entry = new StockTransferEntry();
                $entry->setStockTransfer($transferStock)
                    ->setProduct($prod)
                    ->setQuantity($qty);

                $em->persist($entry);
                $em->flush();


                $em->persist($entry);
                $em->flush();

            }

            $list_opts[] = array(
                'status'=>'success',
                'id'=>$transferStock->getID()
            );

        }



        return new JsonResponse($list_opts);
    }
}
