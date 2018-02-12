<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\StockHistory;
use Gist\InventoryBundle\Entity\Counting;
use Gist\InventoryBundle\Entity\CountingEntry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\ValidationException;
use Gist\InventoryBundle\Model\InventoryException;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Gist\InventoryBundle\Entity\Stock;
use DateTime;
use DateInterval;

class CountingFormController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;

    public function indexAction()
    {
        $inv = $this->get('gist_inventory');
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $this->route_prefix = 'gist_inv_counting_gridform';
        $this->title = 'Counting Form';
        $params = $this->getViewParams('List');
        $this->getControllerBase();
        $gl = $this->setupGridLoader();
        $params['main_warehouse'] = 0;
        $params['grid_cols'] = $gl->getColumns();
        $disabled = $this->checkForCountingSubmission();
        $params['form_disabled'] = $disabled[0];
        $params['detected_submission_timeslot'] = $disabled[1];
        //$params['wh_type_opts'] = array('sales'=>'Sales', 'damaged'=>'Damaged','missing'=>'Missing','tester'=>'Tester');

        $params['stocks'] = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$main_warehouse->getInventoryAccount()->getID()));

//        var_dump(count($params['stocks']));
//        die();

        $params['computation_opts'] = array(
            'manual' => 'Manual',
            'formula' => 'Formula'
        );

        $inv = $this->get('gist_inventory');
        $params['pos_loc_opts'] = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptionsOnly();

        return $this->render('GistInventoryBundle:CountingForm:index.html.twig', $params);
    }

    public function posFormFieldsAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $inv = $this->get('gist_inventory');
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $sysCountVisibility = $config->get('gist_pos_counting_system_count_visibility');
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));

        $stocks = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$pos_location->getInventoryAccount()->getID()));

        $list_opts = [];
        foreach ($stocks as $stock) {

            $stockQ = $stock->getQuantity();
            if (!is_float($stockQ)) {
                $stockQ = round($stockQ);
            }


            $list_opts[] = array(
                'product_id'=>$stock->getProduct()->getID(),
                'item_code'=>$stock->getProduct()->getItemCode(),
                'item_name'=>$stock->getProduct()->getName(),
                'current_stock'=> $stockQ,
                'sys_stock_visibility' => $sysCountVisibility
            );

        }


        return new JsonResponse($list_opts);
    }

    public function posIsFormValidAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $inv = $this->get('gist_inventory');
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $maxSubmissions = $config->get('gist_pos_counting_max_submissions');
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));

        $countings = $em->getRepository('GistInventoryBundle:Counting')->findBy(array('inventory_account'=>$pos_location->getInventoryAccount()->getID(),'date_submitted'=>new DateTime()));

        if ($countings) {
            if (count($countings) >= $maxSubmissions) {
                $list_opts[] = array(
                    0=>true,
                    1=> 'Maximum number of '.$maxSubmissions.' submissions made. Please wait for the next time slot.'
                );
                return new JsonResponse($list_opts);
            }

            //counting/s found but failed in tests. should delete?
            $list_opts[] = array(
                0=>false,
                1=> ''
            );
            return new JsonResponse($list_opts);
        } else {
            $list_opts[] = array(
                0=>false,
                1=> ''
            );
            return new JsonResponse($list_opts);
        }
    }

    public function checkForCountingSubmission()
    {
        $inv = $this->get('gist_inventory');
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $maxSubmissions = $config->get('gist_pos_counting_max_submissions');
        $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $countings = $em->getRepository('GistInventoryBundle:Counting')->findBy(array('inventory_account'=>$main_warehouse->getInventoryAccount()->getID(),'date_submitted'=>new DateTime()));

        if ($countings) {
            if (count($countings) >= $maxSubmissions) {
                return array(true, 'Maximum number of '.$maxSubmissions.' submissions made. Please wait for the next time slot.');
            }

            //counting/s found but failed in tests. should delete?
            return array(false,'');
        } else {
            return array(false,'');
        }
    }

    protected function getRouteGen()
    {
        if ($this->route_gen == null)
            $this->route_gen = new RouteGenerator($this->route_prefix);

        return $this->route_gen;
    }

    public function posFormSubmitAction($pos_loc_id, $uid, $entries)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $maxSubmissions = $config->get('gist_pos_counting_max_submissions');
        $inv = $this->get('gist_inventory');
        $dateNow = new DateTime();

        if (strtotime($dateNow->format('h:i A')) <= strtotime('03:00 AM')) {
            $dateNow = $dateNow->sub(new DateInterval('P1D'));
        }

        $user = $em->getRepository('GistUserBundle:User')->findOneBy(array('id' => $uid));
        parse_str(html_entity_decode($entries), $entriesParsed);
        $posLocation = $inv->findPOSLocation($pos_loc_id);

        $countings = $em->getRepository('GistInventoryBundle:Counting')->findBy(array('inventory_account' => $posLocation->getInventoryAccount()->getID(), 'date_submitted' => new DateTime()));
        if (count($countings) > $maxSubmissions) {
            $list_opts[] = array(
                'status' => 'failed'
            );
            return new JsonResponse($list_opts);
        }

        $counting = new Counting();
        $counting->setInventoryAccount($posLocation->getInventoryAccount());
        $counting->setDateSubmitted($dateNow);
        $counting->setUserCreate($user);
        $counting->setStatus('Submitted');
        $counting->setRemarks('Counting generic remark [POS]');
        $em->persist($counting);
        $em->flush();

        foreach ($entriesParsed as $e) {
            $entries = array();
            $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($e['product_id']);
            $countingEntry = new CountingEntry();
            $countingEntry->setProduct($product);
            $countingEntry->setQuantity($e['count']);
            $countingEntry->setExistingQuantity($e['current']);
            $countingEntry->setCounting($counting);
            $em->persist($countingEntry);
            $em->flush();

            //override stock
            $prod = $product;
            $dmg_acc = $posLocation->getInventoryAccount();
            $adj_acc = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
            $adj_acc = $adj_acc->getInventoryAccount();


            $new_qty = $e['count'];
            $old_qty = $e['current'];

            $remarks = 'POS Counting';
            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($user)
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
            if ($new_qty > $old_qty) {
                $qty = $new_qty - $old_qty;
                $wh_entry->setDebit($qty);
                $adj_entry->setCredit($qty);
            } else {
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
            //end stock manipulation
            //end override stock
        }

        $list_opts[] = array(
            'status'=>'success'
        );

        return new JsonResponse($list_opts);
    }

    public function indexSubmitAction()
    {
        $em = $this->getDoctrine()->getManager();
        $config = $this->get('gist_configuration');
        $inv = $this->get('gist_inventory');
        $data = $this->getRequest()->request->all();
        $dateNow = new DateTime();

        if (strtotime($dateNow->format('h:i A')) <= strtotime('03:00 AM')) {
            $dateNow = $dateNow->sub(new DateInterval('P1D'));
        }

        $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $counting = new Counting();
        $counting->setInventoryAccount($main_warehouse->getInventoryAccount());
        $counting->setDateSubmitted($dateNow);
        $counting->setUserCreate($this->getUser());
        $counting->setStatus('Submitted');
        $counting->setRemarks('Counting generic remark');
        $em->persist($counting);
        $em->flush();

        foreach ($data['product_id'] as $index => $value)
        {
            $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($value);
            $countingEntry = new CountingEntry();
            $countingEntry->setProduct($product);
            $countingEntry->setQuantity($data['currentCount'][$index]);
            $countingEntry->setExistingQuantity($data['existingCount'][$index]);
            $countingEntry->setCounting($counting);
            $em->persist($countingEntry);
            $em->flush();

            //override stock
            $prod = $product;
            $dmg_acc = $main_warehouse->getInventoryAccount();
            $adj_acc = $inv->findWarehouse($config->get('gist_adjustment_warehouse'));
            $adj_acc = $adj_acc->getInventoryAccount();


            $new_qty = $data['currentCount'][$index];
            $old_qty = $data['existingCount'][$index];

            $remarks = 'ERP  Counting';
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
            if ($new_qty > $old_qty) {
                $qty = $new_qty - $old_qty;
                $wh_entry->setDebit($qty);
                $adj_entry->setCredit($qty);
            } else {
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
            //end stock manipulation
            //end override stock
        }

        $this->addFlash('success', 'Submitted successfully.');

        return $this->redirect($this->generateUrl('gist_inv_counting_gridform_index'));

    }

    protected function getViewParams($subtitle = '', $selected = null)
    {
        if ($selected == null && $this->getRouteGen()->getList() != null)
            $selected = $this->getRouteGen()->getList();

        $params = parent::getViewParams($subtitle, $selected);
        $params['route_list'] = $this->getRouteGen()->getList();
        $params['route_add'] = $this->getRouteGen()->getAdd();
        $params['route_edit'] = $this->getRouteGen()->getEdit();
        $params['route_delete'] = $this->getRouteGen()->getDelete();
        $params['route_grid'] = $this->getRouteGen()->getGrid();
        $params['prefix'] = $this->route_prefix;

        $params['base_view'] = $this->base_view;
        return $params;
    }

    protected function setupGridLoader($pos_loc_id = null)
    {
        $this->repo = "GistInventoryBundle:Stock";
        $data = $this->getRequest()->query->all();
        $grid = $this->get('gist_grid');

        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository($this->repo);

        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);

        $gcols = $this->getGridColumns($pos_loc_id);

        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('prod','product','getProduct'),
            $grid->newJoin('inv','inv_account','getInventoryAccount'),
        );
    }


    protected function getGridColumns($pos_loc_id = null)
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Code','getItemCode','item_code', 'prod'),
            $grid->newColumn('Item Name','getID','name', 'prod', array($this,'formatProductLink')),
            $grid->newColumn('Current Stock','getQuantity','quantity', 'o')
        );
    }

    public function formatNumericLinkThreshold($number) {
        return "<div class=\"numeric\"><a style=\"text-decoration: none;\" href=\"javascript:void(0)\" class=\"change_threshold_btn\">".number_format($number, 2)."</a></div>";
    }

    public function formatProductLink($id) {
        $em = $this->getDoctrine()->getManager();
        $router = $this->get('router');
        $obj = $em->getRepository('GistInventoryBundle:Product')->find($id);
        if($obj->getID() != null)
            return "
                <input type=\"hidden\" class=\"row_prod_id\" value=\"".$obj->getID()."\">
                <a style=\"text-decoration: none;\" href=\"".$router->generate('cat_inv_prod_edit_form', array('id' => $obj->getID()))."\">".$obj->getName()."</a>
            ";
        else
            return "-";
    }

    public function formatInv($id) {
        $em = $this->getDoctrine()->getManager();
        $router = $this->get('router');
        $obj = $em->getRepository('GistInventoryBundle:Account')->find($id);
        if($obj->getID() != null)
            return "
                <input type=\"hidden\" class=\"row_inv_id\" value=\"".$obj->getID()."\">".
                $obj->getName();

        else
            return "-";
    }

    public function formatNumeric($number)
    {
        return '<div class="numeric">'.$number.'</div>';
    }

    public function formatStock($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('product'=>$id));

        $product = $stock->getProduct();

        if ($stock->getQuantity() <= $product->getMinStock() || $stock->getQuantity() >= $product->getMaxStock())
        {
            return '<div class="numeric">'.number_format($stock->getQuantity(), 0).'</div>';
        }
        else
        {
            return '<div class="numeric">'.number_format($stock->getQuantity(), 0).'</div>';
        }
    }

    public function gridAction($pos_loc_id = -1)
    {
        $this->getControllerBase();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $gloader = $this->setupGridLoader();
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if($pos_loc_id == 0)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }
        elseif ($pos_loc_id == -1)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }
        else
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
        }

        $qry[] = "(o.quantity > -90000)";

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gloader->setQBFilterGroup($fg);
        }


        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function gridSearchAction($pos_loc_id, $inv_type)
    {
        $this->getControllerBase();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $gloader = $this->setupGridLoader();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if($pos_loc_id == 0)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));

            if ($inv_type == 'sales') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
            } elseif ($inv_type == 'damaged') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getDamagedContainer()->getID()."')";
            } elseif ($inv_type == 'tester') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getTesterContainer()->getID()."')";
            } elseif ($inv_type == 'missing') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getMissingContainer()->getID()."')";
            } else {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
            }


        }
        elseif ($pos_loc_id == -1)
        {

        }
        else
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            if ($inv_type == 'sales') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
            } elseif ($inv_type == 'damaged') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getDamagedContainer()->getID()."')";
            } elseif ($inv_type == 'tester') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getTesterContainer()->getID()."')";
            } elseif ($inv_type == 'missing') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getMissingContainer()->getID()."')";
            } else {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
            }
        }

        $qry[] = "(o.quantity > -90000)";


        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gloader->setQBFilterGroup($fg);
        }
        else
        {

        }

        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function getControllerBase()
    {
        $full = $this->getRequest()->get('_controller');
        $x_full = explode('\\', $full);

        $bundle = $x_full[0] . $x_full[1];
        $x_cont = explode('Controller:', $x_full[3]);
        $name = $x_cont[0];

        $base = $bundle . ':' . $name;

        if ($this->repo == null)
            $this->repo = $base;
        $this->base_view = $base;

        return $base;
    }

    /**
     *
     * (AJAX)
     * @param $trans_sys_id
     * @return JsonResponse
     */
    public function getProductDetailsStockAction($id, $inv_id)
    {
        $split_trans_total = 0;
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('id'=>$id));
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('product'=>$id, 'inv_account'=>$inv_id));

        //calculate min and max based on formula and span in settings
        $f_min = 0;
        $f_max = 0;
        $max = $stock->getMaxStock();
        $min = $stock->getMinStock();

        $list_opts[] = array('name'=>$product->getName(), 'f_min'=>$f_min, 'f_max'=>$f_max, 'min'=>$min, 'max'=>$max);
        return new JsonResponse($list_opts);
    }
}

