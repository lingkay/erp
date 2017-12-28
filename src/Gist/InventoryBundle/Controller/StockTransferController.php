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

//    public function viewAction()
//    {
//        $this->hookPreAction();
//        $gl = $this->setupGridLoader();
//        $params = $this->getViewParams('List', 'hris_report_attendance_view');
//        $twig_file = 'HrisReportBundle:Attendance:view.html.twig';
//        $date_from = new DateTime();
//        $date_to = new DateTime();
//        $date_from->format("Y-m-d");
//        $date_to->format("Y-m-d");
//        $this->padFormParams($params, $date_from, $date_to);
//        $params['date_from'] = $date_from;
//        $params['date_to'] = $date_to;
//        $params['list_title'] = $this->list_title;
//        return $this->render($twig_file, $params);
//    }

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
            $grid->newColumn('Status','getStatus','status'),
            $grid->newColumn('Source','getName','name','s_inv'),
            $grid->newColumn('Destination','getName','name','d_inv'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();

        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + $inv->getPOSLocationOptions();
        $params['item_opts'] = array('000'=>'-- Select Product --') + $inv->getProductOptionsTransfer();
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

//        echo "<pre>";
//        var_dump($data);
//        echo "</pre>";
//
//        die();

        if ($is_new) {
            $o->setStatus('requested');
            $o->setRequestingUser($this->getUser());

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

            $o->setDescription($data['description']);
            $o->setSource($wh_src->getInventoryAccount());
            $o->setDestination($wh_destination->getInventoryAccount());

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
                $o->setProcessedUser($this->getUser());
                $o->setDateProcessed(new DateTime());

            } elseif ($data['status'] == 'delivered') {
                $o->setDeliverUser($this->getUser());
                $o->setDateDelivered(new DateTime());
            } elseif ($data['status'] == 'arrived') {
                $o->setReceivingUser($this->getUser());
                $o->setDateReceived(new DateTime());
            }
        }
    }

    public function printPDFAction($id)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "GistInventoryBundle:StockTransfer:print.html.twig";

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
        $query          ->from('GistInventoryBundle:StockTransfer', 'o');
//        $query          ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee=e.id');


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
        $list_opts = array('-1'=>'-- Select Location --') + array('0'=>'Main Warehouse') + $inv->getPOSLocationOptions();

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
                'destination'=> $st->getDestination()->getName(),
                'date_create'=> $st->getDateCreate()->format('y-m-d H:i:s'),
                'status'=> $st->getStatus(),
                'description'=> $st->getDescription(),
                'user_create' => $st->getRequestingUser()->getDisplayName(),
                'user_processed' => $st->getProcessedUser()->getDisplayName(),
                'user_delivered' => $st->getDeliverUser()->getDisplayName(),
                'user_received' => $st->getReceivingUser()->getDisplayName(),
                'date_processed' => $st->getDateProcessed()->format('y-m-d H:i:s'),
                'date_delivered' => $st->getDateDelivered()->format('y-m-d H:i:s'),
                'date_received' => $st->getDateReceived()->format('y-m-d H:i:s'),
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
            );

        }

        return new JsonResponse($list_opts);
    }

}