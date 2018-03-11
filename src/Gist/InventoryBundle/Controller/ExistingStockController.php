<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\StockHistory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\ValidationException;
use Gist\InventoryBundle\Model\InventoryException;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use DateTime;


class ExistingStockController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;

    protected $date_from;
    protected $inv_account;
    protected $date_to;

    public function __construct()
    {
        $date_from = new DateTime('-3 month');
        $date_to = new DateTime();

        $this->date_from = $date_from->format('Ymd');
        $this->date_to = $date_to->format('Ymd');
        $this->inv_account = '0';
    }

    public function indexAction()
    {
        $config = $this->get('gist_configuration');
        $inv = $this->get('gist_inventory');
        $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
        $this->inv_account = $main_warehouse->getInventoryAccount()->getID();

        $this->route_prefix = 'gist_inv_existing_stock';
        $this->title = 'Existing Stock';
        $params = $this->getViewParams('List');
        $this->getControllerBase();
        $gl = $this->setupGridLoader();

        $params['xx'] = $this->inv_account;

        $params['main_warehouse'] = 0;
        $params['grid_cols'] = $gl->getColumns();
        $params['wh_type_opts'] = array('sales'=>'Sales', 'damaged'=>'Damaged','missing'=>'Missing','tester'=>'Tester');

        $params['computation_opts'] = array(
            'manual' => 'Manual',
            'formula' => 'Formula'
        );

        $inv = $this->get('gist_inventory');
        $params['pos_loc_opts'] = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptionsOnly();

        //added
        $date_from = new DateTime('-3 month');
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        //added
        // $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'GistInventoryBundle:ExistingStock:index.html.twig';

        //added
        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function getRouteGen()
    {
        if ($this->route_gen == null)
            $this->route_gen = new RouteGenerator($this->route_prefix);

        return $this->route_gen;
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
        $params['xx'] = $this->inv_account;
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
            $grid->newColumn('Current Stock','getQuantity','quantity', 'o', [$this, 'formatNumeric']),
            $grid->newColumn('Cost Per Unit','getCost','cost', 'prod', [$this, 'formatNumeric']),
            $grid->newColumn('Total Cost','getID','id', 'prod', [$this, 'formatTotalCost']),
            $grid->newColumn('Piece per package','getPiecePerPackage','piece_per_package', 'prod', [$this, 'formatNumeric']),
            $grid->newColumn('Daily avg. consumption','getID','id', 'prod', [$this, 'formatAvgConsumption']),
            $grid->newColumn('Days w/ Stock','getID','id', 'prod', [$this, 'formatDaysWithStock'])
        );
    }

    public function formatDaysWithStock($prodId)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(['product'=>$prodId, 'inv_account'=>$this->inv_account]);
        $date_from = DateTime::createFromFormat('Ymd', $this->date_from);
        $date_to = DateTime::createFromFormat('Ymd', $this->date_to);
        $quantitySold = $this->getQuantitySold($prodId, $date_from,$date_to);

        $datediff = strtotime($date_from->format('Y-m-d')) - strtotime($date_to->format('Y-m-d'));
        $days = round($datediff / (60 * 60 * 24), 2);

        if ($quantitySold == 0) {
            $daysWithStock = 0;
        } else {
            if ($days == 0) {
                $days = 1;
            }
            $avgConsumption = number_format($quantitySold/$days, 2);
            $daysWithStock = abs($stock->getQuantity() / $avgConsumption);
        }


        return "<div class=\"numeric\">".number_format($daysWithStock, 2)."</div>";
    }

    public function formatAvgConsumption($prodId)
    {
        $date_from = DateTime::createFromFormat('Ymd', $this->date_from);
        $date_to = DateTime::createFromFormat('Ymd', $this->date_to);
        $quantitySold = $this->getQuantitySold($prodId, $date_from,$date_to);
        $datediff = strtotime($date_from->format('Y-m-d')) - strtotime($date_to->format('Y-m-d'));
        $days = round($datediff / (60 * 60 * 24), 2);

        if ($quantitySold == 0) {
            $avgConsumption = 0;
        } else {
            if ($days == 0) {
                $days = 1;
            }
            $avgConsumption = number_format($quantitySold/$days, 2);
        }

        return "<div class=\"numeric\">".abs($avgConsumption)."</div>";
    }

    public function getQuantitySold($productId, $dateFrom, $dateTo)
    {
        $quantitySold = 0;
        $layeredReportService = $this->get('gist_layered_report_service');
        $transactionItems = $layeredReportService->getTransactionItems($dateFrom->format('Y-m-d'), $dateTo->format('Y-m-d'), null, null);

        foreach ($transactionItems as $transactionItem) {
            if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                if ($transactionItem->getTransaction()->getPOSLocation()->getInventoryAccount()->getID() == $this->inv_account) {
                    if ($transactionItem->getProductId() == $productId) {
                        $quantitySold++;
                    }
                }
            }
        }

        return $quantitySold;
    }

    public function formatNumericLinkThreshold($number)
    {
        return "<div class=\"numeric\"><a style=\"text-decoration: none;\" href=\"javascript:void(0)\" class=\"change_threshold_btn\">".number_format($number, 2)."</a></div>";
    }

    public function formatTotalCost($prodId)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(['product'=>$prodId, 'inv_account'=>$this->inv_account]);

        $quantity = $stock->getQuantity();
        $cost = $stock->getProduct()->getCost();

        $total = $quantity * $cost;

        return "<div class=\"numeric\">".number_format($total, 2)."</div>";
    }

    public function formatProductLink($id)
    {
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
        $config = $this->get('gist_configuration');

        $this->getControllerBase();
        $inv = $this->get('gist_inventory');
        $gloader = $this->setupGridLoader();
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if($pos_loc_id == 0)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $this->inv_account = $main_warehouse->getInventoryAccount()->getID();
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }
        elseif ($pos_loc_id == -1)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $this->inv_account = $main_warehouse->getInventoryAccount()->getID();
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }
        else
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            $this->inv_account = $selected_loc->getInventoryAccount()->getID();
            $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
        }

        $qry[] = "(o.quantity > -900000)";

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

    public function gridSearchAction($pos_loc_id, $inv_type, $date_from, $date_to)
    {
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        $this->date_from = $date_from;
        $this->date_to = $date_to;
        $this->getControllerBase();

        $gloader = $this->setupGridLoader();

        $this->date_from = $date_from;

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if($pos_loc_id == 0)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));


            if ($inv_type == 'sales') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
                $this->inv_account = $main_warehouse->getInventoryAccount()->getID();
            } elseif ($inv_type == 'damaged') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getDamagedContainer()->getID()."')";
                $this->inv_account = $main_warehouse->getInventoryAccount()->getDamagedContainer()->getID();
            } elseif ($inv_type == 'tester') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getTesterContainer()->getID()."')";
                $this->inv_account = $main_warehouse->getInventoryAccount()->getTesterContainer()->getID();
            } elseif ($inv_type == 'missing') {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getMissingContainer()->getID()."')";
                $this->inv_account = $main_warehouse->getInventoryAccount()->getMissingContainer()->getID();
            } else {
                $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
                $this->inv_account = $main_warehouse->getInventoryAccount()->getID();
            }


        }
        elseif ($pos_loc_id == -1)
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $this->inv_account = $main_warehouse->getInventoryAccount()->getID();
        }
        else
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            if ($inv_type == 'sales') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
                $this->inv_account = $selected_loc->getInventoryAccount()->getID();
            } elseif ($inv_type == 'damaged') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getDamagedContainer()->getID()."')";
                $this->inv_account = $selected_loc->getInventoryAccount()->getDamagedContainer()->getID();
            } elseif ($inv_type == 'tester') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getTesterContainer()->getID()."')";
                $this->inv_account = $selected_loc->getInventoryAccount()->getTesterContainer()->getID();
            } elseif ($inv_type == 'missing') {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getMissingContainer()->getID()."')";
                $this->inv_account = $selected_loc->getInventoryAccount()->getMissingContainer()->getID();
            } else {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
                $this->inv_account = $selected_loc->getInventoryAccount()->getID();
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

    /**
     *
     * For POS Open Tester Grid
     * (copy damaged items grid implementation)
     *
     * @param $pos_loc_id
     * @return JsonResponse
     */
    public function getExistingStockDataAction($origin, $pos_loc_id, $inv_type)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $origin_pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id' => $origin));
        if ($pos_loc_id == '0') {
            $pos_location = $inv->findWarehouse($config->get('gist_main_warehouse'));
        } else {
            $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id' => $pos_loc_id));
        }

        if ($inv_type == 'sales') {
            $iacc = $pos_location->getInventoryAccount();
        } elseif ($inv_type == 'damaged') {
            $iacc = $pos_location->getInventoryAccount()->getDamagedContainer();
        } elseif ($inv_type == 'tester') {
            $iacc = $pos_location->getInventoryAccount()->getTesterContainer();
        } elseif ($inv_type == 'missing') {
            $iacc = $pos_location->getInventoryAccount()->getMissingContainer();
        } else {
            $iacc = $pos_location->getInventoryAccount();
        }

        $stock = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$iacc->getID()));
        $list_opts = [];

        foreach ($stock as $p) {
            $list_opts[] = array(
                'quantity' => $p->getQuantity(),
                'item_code' =>$p->getProduct()->getItemCode(),
                'barcode' => $p->getProduct()->getBarcode(),
                'item_name' => $p->getProduct()->getName()
            );
        }

        $list_opts = array_map("unserialize", array_unique(array_map("serialize", $list_opts)));
        return new JsonResponse($list_opts);
    }

    public function getPOSVisibilityAction($pos_loc_id)
    {
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $origin_pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id' => $pos_loc_id));

        $list_opts[] = array('other_pos_stock_visible' => $origin_pos_location->getOtherLocStockVisible());
        return new JsonResponse($list_opts);
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

    /**
     *
     * (AJAX)
     * @param $trans_sys_id
     * @return JsonResponse
     */
    public function saveStockThresholdAction($id, $min, $max, $pos_loc_id, $inv_id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($inv_id == 0) {
            if ($pos_loc_id == 0) {
                $inv = $this->get('gist_inventory');
                $config = $this->get('gist_configuration');
                $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
                $posLocation = $main_warehouse;
            } else {
                $posLocation = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
            }

            $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('product'=>$id, 'inv_account'=>$posLocation->getInventoryAccount()->getID()));
        } else {
            $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('product'=>$id, 'inv_account'=>$inv_id));
        }

        try {
            $stock->setMaxStock($max);
            $stock->setMinStock($min);
            $em->persist($stock);
            $em->flush();
            $list_opts[] = array('status'=>'success');
        } catch (\Exception $e) {
            $list_opts[] = array('status'=>'error');
        }

        return new JsonResponse($list_opts);
    }

    protected function filterGrid(){
        $grid = $this->get('gist_grid');
        return $grid->newFilterGroup();
    }
}

