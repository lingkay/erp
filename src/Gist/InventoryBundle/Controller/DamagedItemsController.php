<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Entity\DamagedItems;
use Gist\InventoryBundle\Entity\DamagedItemsEntry;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Stock;
use DateTime;


class DamagedItemsController extends CrudController
{
    use TrackCreate;

    /**
     * DamagedItemsController constructor.
     */
    public function __construct()
    {
        $this->route_prefix = 'gist_inv_damaged_items';
        $this->title = 'Damaged Items';

        $this->list_title = 'Damaged Items';
        $this->list_type = 'dynamic';
        $this->repo = "GistInventoryBundle:DamagedItems";
    }

    /**
     * @param $obj
     * @return string
     */
    protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    /**
     * @return DamagedItemsEntry
     */
    protected function newBaseClass()
    {
        return new DamagedItemsEntry();
    }

    /**
     * Show the index/grid page of damaged items
     *
     * @return mixed
     */
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

    /**
     *
     * Generate index/grid table data
     *
     * @return Response
     */
    public function gridAction()
    {
        $this->hookPreAction();
        $gloader = $this->setupGridLoader();
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function posGridLoaderAction($pos_loc_id = NULL)
    {
        //this could be for gridAction of POS
        $gloader = $this->setupGridLoader();
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    /**
     *
     * Setup table data
     *
     * @param null $pos_loc_id
     * @return mixed
     */
    protected function setupGridLoader($pos_loc_id = NULL)
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository('GistInventoryBundle:DamagedItemsEntry');

        $dmg_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($dmg_src->getID(), 'warehouse');

        if ($pos_loc_id != NULL) {
            $dmg_src = $inv->findPOSLocation($pos_loc_id);
            $dmg_acc = $inv->getDamagedContainerInventoryAccount($dmg_src->getID(), 'warehouse');
        }

        $fg = $grid->newFilterGroup();
        $fg->where('o.source_inv_account = :inv_account')
            ->setParameter('inv_account', $dmg_acc->getID());

//        $fg->andwhere('o.destination_inv_account = :inv_account')
//            ->setParameter('inv_account', $dmg_acc->getID());

        //no need to hide returned. backend will change the entry's source to the destination iacc
//        $fg->andwhere('o.status != :status')
//            ->setParameter('status', 'returned');

        $gloader->setQBFilterGroup($fg);

        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);

//        $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGrid'), false, false);
        $gcols = $this->getGridColumns();

        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGrid'), false, false);

        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    /**
     *
     * Get index/grid table data joins
     *
     * @return array
     */
    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('product','product','getProduct'),
            $grid->newJoin('user','user_create','getUserCreate'),
        );
    }

    /**
     *
     * Setup index/grid table columns and getters
     *
     * @return array
     */
    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            //$grid->newColumn('', 'getID', 'id','o', array($this,'drawCheckbox')),
            $grid->newColumn('Item','getName','name', 'product'),
            $grid->newColumn('Quantity','getQuantity','quantity'),
            $grid->newColumn('Date create','getDateCreateFormatted','date_create'),
            $grid->newColumn('Created by','getDisplayName','last_name','user'),
            $grid->newColumn('Status','getStatusFMTD','status'),
        );
    }

    public function drawCheckbox($id)
    {
        return '';
    }

    /**
     *
     * Custom index/grid table action buttons
     *
     * @param $id
     * @return mixed
     */
    public function callbackGrid($id)
    {
        $em = $this->getDoctrine()->getManager();
        $dmgEntry = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findOneBy(array('id'=>$id));

        $parentID = 0;
        if ($dmgEntry->getDamagedItems()) {
            $parentID = $dmgEntry->getDamagedItems()->getID();
        }
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
            'status' => $dmgEntry->getStatus(),
            'parent_id' => $parentID,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistInventoryBundle:DamagedItems:action.html.twig',
            $params
        );
    }

    /** BEGIN ADD ENTRIES METHODS */

    /**
     *
     * This will show the form for user to add damaged products to damaged items container.
     * All submissions from this page will be displayed on the index/grid page.
     * NOTE: Will NOT SUM quantities of same product.
     *
     * @return mixed
     */
    public function addFormEntriesAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();


        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('GistInventoryBundle:DamagedItems:add_entries.form.html.twig', $params);
    }

    /**
     *
     * This will save new damaged items to damaged items container.
     * Initial status: damaged
     *
     * @return mixed
     */
    public function addSubmitEntriesAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $data = $this->getRequest()->request->all();
        try
        {
            $obj = $this->newBaseClass();
            $this->saveDamages($data);

            $this->addFlash('success', $this->title . ' added successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
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

    /**
     * @param $data
     * @return array
     */
    protected function saveDamages($data)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $entries = array();
        
        foreach ($data['product_item_code'] as $index => $value)
        {
            $prod_item_code = $value;

            // product
            $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            //this is where the damaged items will be transferred virtually
            //if for pos = get pos_loc_id then find from pos_location
            $source = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $dmg_acc = $inv->getDamagedContainerInventoryAccount($source->getID(), 'warehouse');

            //this is where the damaged items will come from
            //this should get from pos location's stock. adjustment warehouse for now
            $adj_warehouse = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
            $adj_acc = $adj_warehouse->getInventoryAccount();

            $new_qty = $data['quantity'][$index];
            $old_qty = 0;

            $remarks = 'Transfer to damaged container';
            if ($data['remarks'][$index] != '') {
                $remarks = $data['remarks'][$index];
            }

            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($this->getUser())
                ->setDescription($remarks);

            // add entries
            // entry for destination
            $wh_entry = new Entry();
            $wh_entry->setInventoryAccount($dmg_acc)
                ->setProduct($prod);

            // entry for source
            $adj_entry = new Entry();
            $adj_entry->setInventoryAccount($adj_acc)
                ->setProduct($prod);

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

            $entry = new DamagedItemsEntry();
            $entry->setProduct($prod)
                ->setQuantity($new_qty);

            $entry->setSource($dmg_acc);
            $entry->setRemarks($remarks);
            $entry->setUserCreate($this->getUser());
            //this will be set when for return
            //$entry->setDestination($dmg_acc);

            $entry->setStatus('damaged');
            $entry->setRequestingUser($this->getUser());

            $em->persist($entry);
            $em->flush();
        }

        return $entries;
        
    }

    /** END ADD ENTRIES METHODS */
    public function getSelectedEntriesAction($ids, $iacc)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        if (strpos($ids, ',') !== false) {
            $product_ids = explode(',', $ids);
        } else {
            $product_ids = array($ids);
        }

        $list_opts = [];

        foreach ($product_ids as $pid) {
            $dmgEntry = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findOneBy(array('id'=>$pid));
            $dmg_stock_qty = $dmgEntry->getQuantity();


            if ($dmg_stock_qty > 0 && $dmgEntry->getStatus() == 'damaged') {
                $list_opts[] = array(
                    'id'=>$dmgEntry->getID(),
                    'item_code'=> $dmgEntry->getProduct()->getItemCode(),
                    'item_name'=> $dmgEntry->getProduct()->getName(),
                    'dmg_stock'=> $dmg_stock_qty,
                );
            }
        }

        if ($iacc == 'posx') {
            return new JsonResponse($list_opts);
        }

        return $list_opts;
    }

    public function addFormReturnAction($ids)
    {
        $this->checkAccess($this->route_prefix . '.add');
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');


        $this->hookPreAction();
        $obj = $this->newBaseClass();

        if (strpos($ids, ',') !== false) {
            $product_ids = explode(',', $ids);
        } else {
            $product_ids = array($ids);
        }

        //change if for POS
        //this is used to get the available stock qty for return (for setting as max allowed)
        $source = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($source->getID(), 'warehouse');

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);
        $params['selected_products'] = $this->getSelectedEntriesAction($ids, $dmg_acc);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    public function posAddReturnAction($description, $user, $source, $destination, $entries)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$user));

        $obj = new DamagedItems();
        $wh_src = $inv->findPOSLocation($source);
        $src_dmg_acc = $inv->getDamagedContainerInventoryAccount($wh_src->getID(), 'pos');


        if ($destination === '0') {
            $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $dmg_acc = $inv->getDamagedContainerInventoryAccount($wh_destination->getID(), 'warehouse');
        } elseif ($destination === '00') {
            $wh_destination = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
        } else {
            $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($destination);
            $dmg_acc = $inv->getDamagedContainerInventoryAccount($wh_destination->getID(), 'pos');
        }

        $obj->setSource($src_dmg_acc);
        $obj->setDestination($dmg_acc);
        $obj->setDescription($description);
        $em->persist($obj);
        $em->flush();

        parse_str(html_entity_decode($entries), $entriesParsed);

        foreach ($entriesParsed as $e)
        {
            $dmgEntryID = $e['entry_id'];
            $dmgEntry = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findOneBy(array('id'=>$dmgEntryID));
            $dmgEntry->setDamagedItems($obj);
            $dmgEntry->setStatus('for return');
            $dmgEntry->setRequestingUser($user);
            $em->persist($dmgEntry);
            $em->flush();
        }

        $list_opts[] = array(
            'status'=>'success'
        );

        return new JsonResponse($list_opts);

    }

    public function addReturnSubmitAction($ids)
    {
        // ASSIGN DMG ENTRIES TO NEW CREATED DAMAGE_ITEMS
        //EDIT SUBMIT - RETURNED - WILL TRIGGER TRANSFER
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $this->checkAccess($this->route_prefix . '.add');
        $data = $this->getRequest()->request->all();

        $this->hookPreAction();
        try
        {
            $obj = new DamagedItems();

            //change if from POS
            $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));

            if ($data['destination'] === '0') {
                $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
            } elseif ($data['destination'] === '00') {
                echo 'here';
                $wh_destination = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
            } else {
                $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($data['destination']);
            }

            $obj->setSource($wh_src->getInventoryAccount()->getDamagedContainer());
            $obj->setDestination($wh_destination->getInventoryAccount()->getDamagedContainer());
            $obj->setDescription($data['description']);
            $em->persist($obj);
            $em->flush();

            foreach ($data['prod_item_code'] as $index => $value)
            {
                $dmgEntryID = $data['entry_id'][$index];
                $dmgEntry = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findOneBy(array('id'=>$dmgEntryID));
                $dmgEntry->setDamagedItems($obj);
                $dmgEntry->setStatus('for return');
                $dmgEntry->setRequestingUser($this->getUser());
                $em->persist($dmgEntry);
                $em->flush();
            }

            $this->addFlash('success', 'Items set for return successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
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

    public function viewFormReceiveAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('GistInventoryBundle:DamagedItems')->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('GistInventoryBundle:DamagedItems:receive_form.html.twig', $params);
    }

    public function posReceiveItemsAction($pos_loc_id, $uid, $id)
    {
        header("Access-Control-Allow-Origin: *");
        $dmgManager = $this->get('gist_inventory_damaged_items_managed');
        //$this->checkAccess($this->route_prefix . '.edit');
        //$this->hookPreAction();

        try
        {
            $dmgManager->updateDamagedEntriesStatus($id, 'returned');
            $dmgManager->transferDamagedItemsToDestination($id, NULL, $uid);

            $list_opts[] = array(
                'status'=>'success'
            );

            return new JsonResponse($list_opts);
        }
        catch (ValidationException $e)
        {
            $list_opts[] = array(
                'status'=>'failed'
            );

            return new JsonResponse($list_opts);
        }
        catch (DBALException $e)
        {
            $list_opts[] = array(
                'status'=>'failed'
            );

            return new JsonResponse($list_opts);
        }
    }

    public function submitFormReceiveAction($id)
    {
        $dmgManager = $this->get('gist_inventory_damaged_items_managed');
        $this->checkAccess($this->route_prefix . '.edit');
        $this->hookPreAction();

        try
        {
            $dmgManager->updateDamagedEntriesStatus($id, 'returned');
            $dmgManager->transferDamagedItemsToDestination($id);

            $this->addFlash('success', 'Items returned successfully.');
            if ($this->submit_redirect) {
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            } else {
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', 'Database error occurred. Possible duplicate.'.$e);
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error occurred. Possible duplicate.'.$e);
            error_log($e->getMessage());
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }

    //view pos recv
    public function getPOSFormDataAction($id, $pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $st = $em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $pos_iacc_id = $pos_location->getInventoryAccount()->getDamagedContainer()->getID();
        $list_opts = [];
        //$main_status = $st->getStatus();



        if ($st->getSource()->getID() == $pos_iacc_id || $st->getDestination()->getID() == $pos_iacc_id) {

            $list_opts[] = array(
                'id'=>$st->getID(),
                'source'=> $st->getSource()->getName(),
                'source_id'=> $st->getSource()->getID(),
                'destination'=> $st->getDestination()->getName(),
                'destination_id'=> $st->getDestination()->getID(),
                'pos_iacc_id' => $pos_iacc_id,
                'date_create'=> $st->getDateCreate()->format('y-m-d H:i:s'),
                'description'=> $st->getDescription(),
                'status'=>$st->getStatus(),
                //'user_create' => $st->getUserCreate()->getDisplayName(),
            );

        } else {

            $list_opts[] = array(
                'id'=>0,
                'source'=> 0,
                'destination'=> 0,
                'destination_id'=> 0,
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

    public function getPOSFormDataEntriesAction($id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $st = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findBy(array('damaged_items'=>$id));

        $list_opts = [];
        foreach ($st as $p) {

            $list_opts[] = array(
                'entry_id'=>$p->getID(),
                'item_code'=>$p->getProduct()->getItemCode(),
                'product_name'=> $p->getProduct()->getName(),
                'quantity'=> $p->getQuantity(),
            );

        }

        //return $list_opts;

        return new JsonResponse($list_opts);
    }

    /** --------------- */

    public function gridSearchToSendAction()
    {
        $this->hookPreAction();
        $this->repo = 'GistInventoryBundle:Product';
        $gloader = $this->setupSummaryGridLoaderToSend();
        $gloader->setQBFilterGroup($this->filterToSend());
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function gridSearchToReceiveAction()
    {
        $this->hookPreAction();
        $this->repo = 'GistInventoryBundle:Product';
        $gloader = $this->setupSummaryGridLoaderToReceive();
        $gloader->setQBFilterGroup($this->filterToRecieve());
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function callbackGridTransmit($id)
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
            'GistInventoryBundle:DamagedItems:action_transmit.html.twig',
            $params
        );
    }

    protected function setupSummaryGridLoaderToReceive()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository('GistInventoryBundle:DamagedItems');

        // grid joins
        $gjoins = $this->getTransmitGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);


        $gcols = $this->getToReceiveGridColumnsAjax();

        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGridTransmit'), false, false);

        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function setupSummaryGridLoaderToSend()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository('GistInventoryBundle:DamagedItems');

        // grid joins
        $gjoins = $this->getTransmitGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);


        $gcols = $this->getToSendGridColumnsAjax();

        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGridTransmit'), false, false);

        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function getTransmitGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
//            $grid->newJoin('product','product','getProduct'),
            $grid->newJoin('iaccs','source_inv_account','getSource'),
            $grid->newJoin('iaccd','destination_inv_account','getDestination'),
        );
    }

    protected function getToSendGridColumnsAjax()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date Create', 'getDateCreateFormatted', 'date_create','o'),
            $grid->newColumn('Destination','getName','name','iaccd'),
            $grid->newColumn('Status', 'getStatus', 'status','o'),
//            $grid->newColumn('Quantity', 'getQuantity', 'quantity','o'),
        );
    }

    protected function getToReceiveGridColumnsAjax()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date Create', 'getDateCreateFormatted', 'date_create','o'),
            $grid->newColumn('Destination','getName','name','iaccs'),
            $grid->newColumn('Status', 'getStatus', 'status','o'),
//            $grid->newColumn('Quantity', 'getQuantity', 'quantity','o'),
        );
    }

    protected function filterToSend()
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $dmg_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($dmg_src->getID(), 'warehouse');

        $qry[] = "(o.source_inv_account = '".$dmg_acc->getID()."')";

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    protected function filterToRecieve()
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $dmg_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($dmg_src->getID(), 'warehouse');

        $qry[] = "(o.destination_inv_account = '".$dmg_acc->getID()."')";

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    /** FOR INDEX/GRID SUMMARY */
    public function gridSearchSummaryAction()
    {
        $this->hookPreAction();
        $this->repo = 'GistInventoryBundle:Product';
        $gloader = $this->setupSummaryGridLoaderAjax();
        $gloader->setQBFilterGroup($this->filterSummary());
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function setupSummaryGridLoaderAjax()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository('GistInventoryBundle:Stock');

        // grid joins
        $gjoins = $this->getSummaryGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);


        $gcols = $this->getSummaryGridColumnsAjax();

        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGridAjax'), false, false);

        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function getSummaryGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('product','product','getProduct'),
        );
    }

    protected function getSummaryGridColumnsAjax()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Code', 'getItemCode', 'item_code','product'),
            $grid->newColumn('Barcode','getBarcode','barcode','product'),
            $grid->newColumn('Name', 'getName', 'name','product'),
            $grid->newColumn('Quantity', 'getQuantity', 'quantity','o'),
        );
    }

    protected function filterSummary()
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $dmg_src = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($dmg_src->getID(), 'warehouse');

        $qry[] = "(o.inv_account = '".$dmg_acc->getID()."')";

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    /** END FOR INDEX/GRID SUMMARY */

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = $inv->getPOSLocationTransferOptionsOnly();
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

        if ($is_new) {

            $o->setDescription($data['description']);
            $entries = array();

            $em->persist($o);
            $em->flush();

            foreach ($data['product_item_code'] as $index => $value)
            {
                $prod_item_code = $value;
                $qty = $data['quantity'][$index];

                $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                $entry = new DamagedItemsEntry();
                $entry->setDamagedItems($o)
                    ->setProduct($prod)
                    ->setQuantity($qty);

                $wh_src = $inv->findWarehouse($config->get('gist_main_warehouse'));

                if ($data['destination'][$index] === '0') {
                    $wh_destination = $inv->findWarehouse($config->get('gist_main_warehouse'));
                } elseif ($data['destination'][$index] === '00') {
                    echo 'here';
                    $wh_destination = $inv->findWarehouse($config->get('gist_damaged_items_warehouse'));
                } else {
                    $wh_destination = $em->getRepository('GistLocationBundle:POSLocations')->find($data['destination'][$index]);
                }

                $entry->setSource($wh_src->getInventoryAccount());
                $entry->setDestination($wh_destination->getInventoryAccount());
                $entry->setStatus('requested');
                $entry->setRequestingUser($this->getUser());
                $em->persist($entry);
                $em->flush();

                $entries[] = $entry;
            }
            return $entries;
        }
    }

    protected function setupGridLoaderAjax()
    {
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository($this->repo);

        $gcols = $this->getGridColumnsAjax();

        if ($this->list_type == 'dynamic')
            $gcols[] = $grid->newColumn('', 'getID', null, 'o', array($this, 'callbackGridAjax'), false, false);

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
    public function getDMGStockListPOSAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $stock_transfer_entries = $em->getRepository('GistInventoryBundle:DamagedItemsEntry')->findBy(array('source_inv_account'=>$pos_location->getInventoryAccount()->getDamagedContainer()->getID()));


        $list_opts = [];
        foreach ($stock_transfer_entries as $p) {
            $parent_id = '0';
            if ($p->getDamagedItems() != null) {
                $parent_id = $p->getDamagedItems()->getID();
            }
            $list_opts[] = array(
                'parent_id' =>$parent_id,
                'id' =>$p->getID(),
                'status' =>$p->getStatus(),
                'statusFMTD' =>$p->getStatusFMTD(),
                'date_create' => $p->getDateCreate()->format('y-m-d H:i:s'),
                'user_create' => $p->getUserCreate()->getDisplayName(),
                'product_name' => $p->getProduct()->getName(),
                'quantity' => $p->getQuantity(),
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
    public function getSentFromPOSAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $stock_transfer_entries = $em->getRepository('GistInventoryBundle:DamagedItems')->findBy(array('source_inv_account'=>$pos_location->getInventoryAccount()->getDamagedContainer()->getID()));


        $list_opts = [];
        foreach ($stock_transfer_entries as $p) {
            $list_opts[] = array(
                'id' =>$p->getID(),
                'destination' => $p->getDestination()->getName(),
                'status' => $p->getStatus(),
                'date_create' => $p->getDateCreate()->format('y-m-d H:i:s'),
                //'user_create' => $p->getUserCreate()->getDisplayName(),
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
        $stock_transfer_entries = $em->getRepository('GistInventoryBundle:DamagedItems')->findBy(array('destination_inv_account'=>$pos_location->getInventoryAccount()->getDamagedContainer()->getID()));

        $list_opts = [];
        foreach ($stock_transfer_entries as $p) {
            $list_opts[] = array(
                'id' =>$p->getID(),
                'source' => $p->getSource()->getName(),
                'status' => $p->getStatus(),
                'date_create' => $p->getDateCreate()->format('y-m-d H:i:s'),
                //'user_create' => $p->getUserCreate()->getDisplayName(),
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
    public function getPOSDamageStockAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$pos_location->getInventoryAccount()->getDamagedContainer()->getID()));

        $list_opts = [];
        foreach ($stock as $p) {
            $list_opts[] = array(
                'item_code' =>$p->getProduct()->getItemCode(),
                'barcode' => $p->getProduct()->getBarcode(),
                'item_name' => $p->getProduct()->getName(),
                'quantity' => $p->getQuantity(),
                //'user_create' => $p->getUserCreate()->getDisplayName(),
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
        $list_opts = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptions();

        return new JsonResponse($list_opts);
    }

    /**
     *
     * Function for POS to fetch prod cat options
     *
     * @return JsonResponse
     */
    public function getProductCategoryOptionsAction()
    {
        header("Access-Control-Allow-Origin: *");
        $prodManager = $this->get('gist_inventory_product_manager');
        $cat_opts = $prodManager->getPOSProductCategoryOptions();
        return new JsonResponse($cat_opts);
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

    public function posAddDamagedEntriesAction($loc_id, $uid, $entries, $posx)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$uid));
        parse_str(html_entity_decode($entries), $entriesParsed);


        foreach ($entriesParsed as $e) {
            $prod_item_code = $e['item_code'];
            $qty = $e['quantity'];
            $remarks = $e['remarks'];
            $this->savePOSDamages($loc_id, $prod_item_code, $qty, $remarks, $user);
        }

        $list_opts[] = array(
            'status'=>'success'
        );

        return new JsonResponse($list_opts);


    }

    protected function savePOSDamages($loc_id, $prod_item_code, $qty, $remarks, $user)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $entries = array();


        // product
        $prod = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
        if ($prod == null)
            throw new ValidationException('Could not find product.');

        $source = $inv->findPOSLocation($loc_id);
        $dmg_acc = $inv->getDamagedContainerInventoryAccount($source->getID(), 'pos');

        //change this to come from POS
        $adj_warehouse = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
        $adj_acc = $adj_warehouse->getInventoryAccount();

        $new_qty = $qty;
        $old_qty = 0;


        if ($remarks == '') {
            $remarks = '[POS] Transfer to damaged container';
        }

        // setup transaction
        $trans = new Transaction();
        $trans->setUserCreate($this->getUser())
            ->setDescription($remarks);

        // add entries
        // entry for destination
        $wh_entry = new Entry();
        $wh_entry->setInventoryAccount($dmg_acc)
            ->setProduct($prod);

        // entry for source
        $adj_entry = new Entry();
        $adj_entry->setInventoryAccount($adj_acc)
            ->setProduct($prod);

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

        $entry = new DamagedItemsEntry();
        $entry->setProduct($prod)
            ->setQuantity($new_qty);

        $entry->setSource($dmg_acc);
        $entry->setRemarks($remarks);
        $entry->setUserCreate($user);
        //this will be set when for return
        //$entry->setDestination($dmg_acc);

        $entry->setStatus('damaged');
        $entry->setRequestingUser($user);

        $em->persist($entry);
        $em->flush();


        return $entries;

    }

    /**
     *
     * Function for POS to add stock transfer
     *
     * @param $src
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
