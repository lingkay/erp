<?php

namespace Gist\SalesReportBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Gist\LocationBundle\LocationRegion;
use DateTime;
use ReflectionClass;

class SalesLayeredReportController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;

    public function __construct()
    {
        $this->route_prefix = 'gist_layered_sales_report_sales';
        $this->title = 'Layered Report - Sales';

        $this->list_title = 'Layered Report - Sales';
        $this->list_type = 'static';
    }

    // FOR TOP LAYER
    public function indexAction($date_from = null, $date_to = null)
    {
        $data = $this->getRequest()->request->all();
        $this->route_prefix = 'gist_layered_sales_report_sales';
        $params = $this->getViewParams('List');
        //$this->getControllerBase();

        //PARAMS


        if (isset($data['date_from']) && isset($data['date_to'])) {
            $date_from = DateTime::createFromFormat('Ymd', $data['date_from']);
            $date_to = DateTime::createFromFormat('Ymd', $data['date_to']);
            $params['date_from'] = $date_from->format("m/d/Y");
            $params['date_to'] = $date_to->format("m/d/Y");
            $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
            $params['date_from_url'] = $date_from->format("m-d-Y");
            $params['date_to_url'] = $date_to->format("m-d-Y");
        } else {
            if ($date_from != null) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $date_from_twig = $date_from->format("m/01/Y");
                $date_to_twig = $date_to->format("m/t/Y");
            } else {
                $date_from = new DateTime();
                $date_to = new DateTime();
                $date_from_twig = $date_from->format("m/01/Y");
                $date_to_twig = $date_to->format("m/t/Y");
            }

            $params['date_from'] = $date_from_twig;
            $params['date_to'] = $date_to_twig;
            $params['date_from_url'] = $date_from->format("m-01-Y");
            $params['date_to_url'] = $date_to->format("m-t-Y");
            $params['all_data'] = $this->getAllData($date_from->format('Y-m-01'), $date_to->format('Y-m-t'));
        }


        return $this->render('GistSalesReportBundle:SalesLayered:index.html.twig', $params);
    }

    protected function getAllData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $data = $layeredReportService->getTransactions($date_from, $date_to, null, null);

        $total_payments = 0;
        $total_cost = 0;
        $total_profit = 0;

        foreach ($data as $d) {
            if (!$d->hasChildLayeredReport()) {
                $total_payments += $d->getTransactionTotal();

                foreach ($d->getItems() as $item) {
                    $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($item->getProductId());
                    $total_cost += $product->getCost();
                }
            }
        }

        $total_profit = $total_payments - $total_cost;

        return [
            'total_sales' => number_format($total_payments, 2, '.',','),
            'total_cost' => number_format($total_cost, 2, '.',','),
            'total_profit' => number_format($total_profit, 2, '.',','),
        ];
    }
    //END TOP LAYER
    //FOR POSITIONS/L2
    public function positionsIndexAction($date_from = null, $date_to = null, $position = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_employee';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['position'] = $position;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['positions_data'] = $this->getPositionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");



                return $this->render('GistSalesReportBundle:SalesLayered:positions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }
        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getPositionsData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        //get all positions
        $salesDept = $em->getRepository('GistUserBundle:Department')->findOneBy(['department_name'=>'Sales']);
        $allPositions = $em->getRepository('GistUserBundle:Group')->findBy(['department'=>$salesDept->getID()]);


        foreach ($allPositions as $position) {
            //initiate totals
            $positionId = $position->getID();
            $totalSales = 0;
            $totalCost = 0;
            $transactionIds = array();

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $user = $em->getRepository('GistUserBundle:User')->findOneById($transactionItem->getTransaction()->getUserCreate()->getID());
                    if ($user->getGroup()->getID() == $positionId) {
                        //$totalCost += $product->getCost();
                        $totalSales += $transactionItem->getTotalAmount();
                        //store transaction id of item for use
                        //array_push($brandTransactionIds, $transactionItem->getTransaction()->getID());
                    }
                }
            }

            $brandTotalProfit = $totalSales - $totalCost;

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'position_id' => $positionId,
                'position_name' => $position->getName(),
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($brandTotalProfit, 2, '.',','),
            );
        }

        if (count($allPositions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END POSITIONS/L2
    //FOR EMPLOYEES/L3 / SHOW EMPLOYEES
    public function employeesIndexAction($date_from = null, $date_to = null, $position = null)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_employee';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['position '] = $position ;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['employees_data'] = $this->getEmployeesData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $position);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");


                $positionObject = $em->getRepository('GistUserBundle:Group')->findOneById($position);

                $params['position_id'] = $positionObject->getID();
                $params['position_name'] = $positionObject->getName();

                return $this->render('GistSalesReportBundle:SalesLayered:employees.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getEmployeesData($date_from, $date_to, $position)
    {
        $em = $this->getDoctrine()->getManager();
        //get all brands
        $allEmployees = $em->getRepository('GistUserBundle:User')->findBy(['group'=>$position]);

        foreach ($allEmployees as $employee) {
            //initiate totals
            $employeeId = $employee->getID();
            $totalSales = 0;
            $totalCost = 0;
            $transactionIds = array();

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $employeex = $em->getRepository('GistUserBundle:User')->findOneById($transactionItem->getTransaction()->getUserCreate()->getID());
                    if ($employeex->getID() == $employeeId && $employeex->getGroup()->getID() == $position) {
                        //$totalCost += $product->getCost();
                        $totalSales += $transactionItem->getTotalAmount();
                        //store transaction id of item for use
                        //array_push($brandTransactionIds, $transactionItem->getTransaction()->getID());
                    }
                }
            }

            $brandTotalProfit = $totalSales - $totalCost;



            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'employee_id' => $employeeId,
                'employee_name' => $employee->getDisplayName(),
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($brandTotalProfit, 2, '.',','),
            );
        }

        if (count($allEmployees) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END AREAS/L3
    //FOR POS LOCS/L4 / SHOW POS LOCATIONS
    public function posIndexAction($date_from = null, $date_to = null, $position = null)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_employee';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['region '] = $region ;
            $params['area'] = $area;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['pos_data'] = $this->getPOSData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'),$region, $area);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                $ref = new ReflectionClass('Gist\LocationBundle\LocationRegion');
                $region_name = $ref->getConstant($region);
                $areaObject = $em->getRepository('GistLocationBundle:Areas')->findOneById($area);

                $params['region_id'] = $region;
                $params['region_name'] = $region_name;
                $params['area_id'] = $areaObject->getID();
                $params['area_name'] = $areaObject->getName();

                return $this->render('GistSalesReportBundle:LocationLayered:locations.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getPOSData($date_from, $date_to, $region, $area)
    {
        $em = $this->getDoctrine()->getManager();
        //get all categories
        $ref = new ReflectionClass('Gist\LocationBundle\LocationRegion');
        $region_name = $ref->getConstant($region);

        $allPOS = $em->getRepository('GistLocationBundle:POSLocations')->findBy([
            'area' => $area,
            'region' => $region
        ]);

        //$regionObject = $em->getRepository('GistLocationBundle:POSLocations')->findOneById($region);
        $areaObject = $em->getRepository('GistLocationBundle:Areas')->findOneById($area);

        foreach ($allPOS as $POSObject) {
            //initiate totals
            $productId = $POSObject->getID();
            $totalSales = 0;
            $totalCost = 0;

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $pos_loc = $em->getRepository('GistLocationBundle:POSLocations')->findOneById($transactionItem->getTransaction()->getPOSLocation());
                    if ($pos_loc->getRegion() == $region && $pos_loc->getArea()->getID() == $area) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }
            }

            $totalProfit = $totalSales - $totalCost;

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'pos_loc_id' => $POSObject->getID(),
                'region_id' => $region,
                'area_id' => $area,
                'pos_name' => $POSObject->getName(),
                'region_name' => $region_name,
                'area_name' => $areaObject->getName(),
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($totalProfit, 2, '.',','),
            );
        }

        if (count($allPOS) > 0) {
            return $list_opts;
        } else {
            return null;
        }

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
        $params['list_title'] = $this->list_title;
        $params['prefix'] = $this->route_prefix;

        $params['base_view'] = $this->base_view;
        return $params;
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
}

