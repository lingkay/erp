<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\StockTransfer;
use Gist\InventoryBundle\Entity\StockTransferEntry;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Stock;
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

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $params['main_status'] = '';
        if ($obj->getID() != '') {
            $params['main_status'] = $obj->getStatus();
        }

        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
    }

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();


        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;
        $params['main_status'] = '';

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    public function editRollbackFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);
        $params['is_rolled_back'] = 'true';

        if ($obj->getStatus() == 'requested') {
            $params['main_status'] = '';
        } elseif ($obj->getStatus() == 'processed') {
            $params['main_status'] = 'requested';
        } elseif ($obj->getStatus() == 'delivered') {
            $params['main_status'] = 'processed';
        }


        return $this->render('GistTemplateBundle:Object:edit.html.twig', $params);
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
        $params['wh_opts'] = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptionsOnly();
        $params['item_opts'] = $inv->getProductOptionsTransfer();

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

    public function addSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');
        $data = $this->getRequest()->request->all();

        $this->hookPreAction();
        try
        {
            $obj = $this->newBaseClass();
            $this->add($obj);

            $this->addFlash('success', $this->title . ' added successfully.');

            if ($data['sp_flag'] == 'true') {
                return $this->redirect($this->generateUrl('gist_inv_stock_transfer_print',array('id'=>$obj->getID())));
            } else {
                if($this->submit_redirect){
                    return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
                }else{
                    return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
                }
            }
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', 'Database error occured. Possible duplicate.'.$e);
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error occured. Possible duplicate.'.$e);
            error_log($e->getMessage());
            return $this->addError($obj);
        }
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

    public function editSubmitAction($id)
    {
        $this->checkAccess($this->route_prefix . '.edit');

        $this->hookPreAction();
        try
        {
            $em = $this->getDoctrine()->getManager();
            $data = $this->getRequest()->request->all();

            $object = $em->getRepository($this->repo)->find($id);
            if ($object->getStatus() == 'arrived') {
                $this->addFlash('error', 'Stock transfer already arrived to destination!');
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(), array('id' => $id)) . $this->url_append);
            }
            // validate
            $this->validate($data, 'edit');
            // update db
            $this->update($object, $data, false);
            $em->flush();
            $this->hookPostSave($object);
            // log
            $odata = $object->toData();
            $this->logUpdate($odata);

            if ($data['sp_flag'] == 'true') {
                return $this->redirect($this->generateUrl('gist_inv_stock_transfer_print',array('id'=>$object->getID())));
            } else {
                $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' edited successfully.');
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(), array('id' => $id)) . $this->url_append);
            }
        }
        catch (ValidationException $e)
        {
            $this->addFlash('Database error occurred. Possible duplicate.');
            return $this->editError($object, $id);
        }
        catch (DBALException $e)
        {
            $this->addFlash('Database error occurred. Possible duplicate.');
            error_log($e->getMessage());

            return $this->editError($object, $id);
        }
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $inv_stock_transfer = $this->get('gist_inventory_stock_transfer');
        if (isset($data['selected_user'])) {
            $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$data['selected_user']));
        } else {
            $user = $this->getUser();
        }

        if ($is_new) {
            $entries = $inv_stock_transfer->saveNewForm($o, $data, $user);
            return $entries;

        } else {
            $inv_stock_transfer->updateForm($o, $data, $user);

        }
    }

    /**
     * @param $id
     * @return mixed
     */
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

    /**
     * @param $id
     * @return mixed
     */
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

        $inv = $this->get('gist_inventory');
        $list_opts = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptions();
        unset($list_opts[$pos_loc_id]);
        return new JsonResponse($list_opts);
    }

    public function getLocationOptions2Action($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");

        $inv = $this->get('gist_inventory');
        $list_opts = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptions();
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
        $invStock = $this->get('gist_inventory_stock_transfer');
        $params = $invStock->getPOSFormData(null, $id, $pos_loc_id);

        return new JsonResponse($params);
    }

    /**
     *
     * Function for POS to fetch stock transfer form data
     *
     * @param $id
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getPOSFormRollbackDataAction($id, $pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $invStock = $this->get('gist_inventory_stock_transfer');
        $params = $invStock->getPOSFormData(null, $id, $pos_loc_id, true);

        return new JsonResponse($params);
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
        $invStock = $this->get('gist_inventory_stock_transfer');
        $params = $invStock->getPOSFormDataEntries(null, $id);

        return new JsonResponse($params);
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
        parse_str($entries, $entriesParsed);
        $stockManipulationManager = $this->get('gist_stock_manipulation_manager');
        $em = $this->getDoctrine()->getManager();
        $st = $em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$userId));
        $inv_stock_transfer = $this->get('gist_inventory_stock_transfer');
        $st->setStatus($status);

        if($status == 'processed') {
            $st->setProcessedUser($user);
            $st->setDateProcessed(new DateTime());
            $inv_stock_transfer->updateStockTransferEntriesProcessed($entriesParsed, $st);
        } elseif ($status == 'delivered') {
            $st->setDeliverUser($user);
            $st->setDateDelivered(new DateTime());
            $stockManipulationManager->performTransferToVirtual($entriesParsed, $st, $user, 'POS');
        } elseif ($status == 'arrived') {
            $st->setReceivingUser($user);
            $st->setDateReceived(new DateTime());
            $inv_stock_transfer->updateStockTransferEntriesArrived($entriesParsed, $st);
            $stockManipulationManager->performTransfer($entriesParsed, $st, $user, 'POS');

        } elseif ($status == 'requested') {
            $inv_stock_transfer->removeEntries($st);
            $inv_stock_transfer->updateStockTransferEntriesRequested($entriesParsed, $st);
        } else {
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

    public function getCurrentStockAction($item_code, $source_id)
    {
        header("Access-Control-Allow-Origin: *");
        $inv = $this->get('gist_inventory');
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');

        $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$item_code));

        if ($source_id == '0') {
            $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
        } else {
            $wh_src = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(['id'=>$source_id]);
        }

        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('inv_account'=>$wh_src->getInventoryAccount()->getID(),'product'=>$prod->getID()));

        if ($stock) {
            $list_opts = array(
                'stock_qty'=>$stock->getQuantity()
            );
        } else {
            $list_opts = array(
                'stock_qty'=>"0.00"
            );
        }


        return new JsonResponse($list_opts);
    }

    public function updatePOSStockTransferFormAction($user, $entries, $id)
    {
        $inv_stock_transfer = $this->get('gist_inventory_stock_transfer');
        $inv_stock_transfer->updatePOSForm($user, $entries, $id);
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

                $entry = new StockTransferEntry();
                $entry->setStockTransfer($st)
                    ->setProduct($prod)
                    ->setQuantity($qty);

                $em->persist($entry);
                $em->flush();
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

            foreach ($entriesParsed as $e) {
                $prod_item_code = $e['code'];
                $qty = $e['quantity'];
                $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code' => $prod_item_code));
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

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
