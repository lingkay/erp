<?php

namespace Gist\InventoryBundle\Controller;

// use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Exception\RouteNotFoundException;


class LocationController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;
//    protected $inv_acct_id;

    public function indexAction()
    {
        $this->route_prefix = 'gist_inv_loc';
        $this->title = 'Dashboard';
        $config = $this->get('gist_configuration');
        $inv = $this->get('gist_inventory');
        $params = $this->getViewParams('List');
        $settings = $this->get('hris_settings');
        $recruitment = $this->get('hris_recruitment');
        $request = $this->get('hris_request');
        $this->getControllerBase();
        $gl = $this->setupGridLoader();
        $params['main_warehouse'] = 0;
        $params['grid_cols'] = $gl->getColumns();
//        $this->inv_acct_id = '0';

        $params['computation_opts'] = array(
            'manual' => 'Manual',
            'formula' => 'Formula'
        );

        $inv = $this->get('gist_inventory');
        $params['pos_loc_opts'] = $inv->getPOSLocationOptions();

        return $this->render('GistInventoryBundle:Location:index.html.twig', $params);
    }

    protected function getRouteGen()
    {
        if ($this->route_gen == null)
            $this->route_gen = new RouteGenerator($this->route_prefix);

        return $this->route_gen;
    }

    protected function getViewParams($subtitle = '', $selected = null)
    {
        // default to list route if nothing selected
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

        // loader
        $gloader = $grid->newLoader();
        $gloader->processParams($data)
            ->setRepository($this->repo);

        // grid joins
        $gjoins = $this->getGridJoins();
        foreach ($gjoins as $gj)
            $gloader->addJoin($gj);

        // grid columns
        $gcols = $this->getGridColumns($pos_loc_id);



        // add columns
        foreach ($gcols as $gc)
            $gloader->addColumn($gc);

        return $gloader;
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('prod','product','getProduct'),
        );
    }


    protected function getGridColumns($pos_loc_id = null)
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Item Code','getItemCode','item_code', 'prod'),
            $grid->newColumn('Item Barcode','getBarcode','item_code', 'prod'),
            $grid->newColumn('Item Name','getID','name', 'prod', array($this,'formatProductLink')),
            $grid->newColumn('Min. Stock','getMinStock','min_stock', 'o', array($this,'formatNumericLinkThreshold')),
            $grid->newColumn('Max. Stock','getMaxStock','max_stock', 'o', array($this,'formatNumericLinkThreshold')),
            $grid->newColumn('Current Stock','getQuantity','quantity', 'o', array($this,'formatNumeric')),
//            $grid->newColumn('Current Stock','getID','quantity','o',array($this,'formatStock')),
        );
    }

    public function formatNumericLinkThreshold($number) {
//        $em = $this->getDoctrine()->getManager();
//        $router = $this->get('router');
//        $obj = $em->getRepository('GistInventoryBundle:Product')->find($id);
//        if($obj->getID() != null)
            return "<div class=\"numeric\"><a style=\"text-decoration: none;\" href=\"javascript:void(0)\" class=\"change_threshold_btn\">".number_format($number, 0)."</a></div>";
//        else
//            return "-";
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

    public function formatNumeric($number)
    {
        return '<div class="numeric">'.number_format($number, 0).'</div>';
    }

    public function formatStock($id)
    {
        $em = $this->getDoctrine()->getManager();
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findOneBy(array('product'=>$id));

        $product = $stock->getProduct();

        if ($stock->getQuantity() <= $product->getMinStock() || $stock->getQuantity() >= $product->getMaxStock())
        {
            return '<div class="numeric" style="color: red;">'.number_format($stock->getQuantity(), 0).'</div>';
        }
        else
        {
            return '<div class="numeric">'.number_format($stock->getQuantity(), 0).'</div>';
        }
    }

    public function gridAction($pos_loc_id = null)
    {
        $this->getControllerBase();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $gloader = $this->setupGridLoader();

//        $gloader->setQBFilterGroup($this->filterGrid());

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();



        if($pos_loc_id != 0)
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
        }
        else
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }


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

    public function gridSearchAction($pos_loc_id)
    {
        $this->getControllerBase();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');
        $gloader = $this->setupGridLoader();

//        $gloader->setQBFilterGroup($this->filterGrid());

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();



        if($pos_loc_id != 0)
        {
            $selected_loc = $inv->findPOSLocation($pos_loc_id);
            if ($selected_loc->getInventoryAccount()) {
                $qry[] = "(o.inv_account = '".$selected_loc->getInventoryAccount()->getID()."')";
            }

        }
        else
        {
            $main_warehouse = $inv->findWarehouse($config->get('gist_main_warehouse'));
            $qry[] = "(o.inv_account = '".$main_warehouse->getInventoryAccount()->getID()."')";
        }


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

    protected function getControllerBase()
    {
        $full = $this->getRequest()->get('_controller');

        // parse out the things we need
        // NOTE: this assumes the format: <namespace>\<bundle_name>\Controller\<controller_name>Controller::<action>
        $x_full = explode('\\', $full);

        $bundle = $x_full[0] . $x_full[1];
        $x_cont = explode('Controller:', $x_full[3]);
        $name = $x_cont[0];

        $base = $bundle . ':' . $name;

        // automatically set repo and base view
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
    public function getProductDetailsStockAction($id)
    {
        $split_trans_total = 0;
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository('GistInventoryBundle:Product')->findOneBy(array('id'=>$id));

        //calculate min and max based on formula and span in settings
        $f_min = 0;
        $f_max = 0;

        $list_opts[] = array('name'=>$product->getName(), 'f_min'=>$f_min, 'f_max'=>$f_max);
        return new JsonResponse($list_opts);
    }

    protected function filterGrid(){
        $grid = $this->get('gist_grid');
        return $grid->newFilterGroup();
    }
}
