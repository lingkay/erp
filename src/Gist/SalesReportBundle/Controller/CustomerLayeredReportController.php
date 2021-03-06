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

class CustomerLayeredReportController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;

    public function __construct()
    {
        $this->route_prefix = 'gist_layered_sales_report_customer';
        $this->title = 'Layered Report - Customer';
        $this->list_title = 'Layered Report - Customers';
        $this->list_type = 'static';
    }

    // FOR TOP LAYER
    public function indexAction($date_from = null, $date_to = null)
    {
        $data = $this->getRequest()->request->all();
        $this->route_prefix = 'gist_layered_sales_report_customer';
        $params = $this->getViewParams('List');
        $params['date_opts'] = ['1' => 'By range', '2' => 'By end date'];
        $params['selected_date_opt'] = '1';

        if (isset($data['date_from']) && isset($data['date_to']) && isset($data['all'])) {
            if ($data['all'] == 'false') {
                $date_from = DateTime::createFromFormat('Ymd', $data['date_from']);
                $date_to = DateTime::createFromFormat('Ymd', $data['date_to']);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
            } else {
                $params['selected_date_opt'] = '2';
                $date_from = DateTime::createFromFormat('m/d/Y', $data['date_from_all']);
                $date_to = DateTime::createFromFormat('m/d/Y', $data['date_to_all']);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
            }
        } else {
            if ($date_from != null) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $date_from_twig = $date_from->format("m/d/Y");
                $date_to_twig = $date_to->format("m/d/Y");
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
            } else {
                $date_from = new DateTime();
                $date_to = new DateTime();
                $date_from_twig = $date_from->format("m/01/Y");
                $date_to_twig = $date_to->format("m/t/Y");
                $params['date_from_url'] = $date_from->format("m-01-Y");
                $params['date_to_url'] = $date_to->format("m-t-Y");
                $params['all_data'] = $this->getAllData($date_from->format('Y-m-01'), $date_to->format('Y-m-t'));
            }

            $params['date_from'] = $date_from_twig;
            $params['date_to'] = $date_to_twig;
        }

        return $this->render('GistSalesReportBundle:CustomerLayered:index.html.twig', $params);
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
    public function customersIndexAction($date_from = null, $date_to = null, $position = null)
    {
        try {
            $this->route_prefix = 'gist_layered_sales_report_customer';
            $params = $this->getViewParams('List');
            $this->getControllerBase();
            $params['position'] = $position;
            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['positions_data'] = $this->getCustomersData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                return $this->render('GistSalesReportBundle:CustomerLayered:customers.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }
        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getCustomersData($date_from, $date_to)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $salesDept = $em->getRepository('GistUserBundle:Department')->findOneBy(['department_name'=>'Sales']);
        $allCustomers = $em->getRepository('GistCustomerBundle:Customer')->findAll();


        foreach ($allCustomers as $customer) {
            $customerId = $customer->getID();
            $totalSales = 0;
            $totalCost = 0;
            $transactionIds = array();

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    if ($transactionItem->getTransaction()->getCustomer()->getID() == $customer->getID()) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }
            }

            $brandTotalProfit = $totalSales - $totalCost;
            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'customer_id' => $customer->getID(),
                    'customer_name' => $customer->getNameFormatted(),
                    'customer_display_id' => $customer->getDisplayID(),
                    'total_sales' => number_format($totalSales, 2, '.', ','),
                    'total_cost' => number_format($totalCost, 2, '.', ','),
                    'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
                );
            }
        }

        if (count($allCustomers) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END POSITIONS/L2

    //FOR EMPLOYEES/L3 / SHOW EMPLOYEES
    public function transactionsIndexAction($date_from = null, $date_to = null, $customer_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_customer';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            $params['$customer_id'] = $customer_id ;
            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->getCustomerTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $customer_id);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                $customerObject = $em->getRepository('GistCustomerBundle:Customer')->findOneById($customer_id);
                $params['customer_id'] = $customerObject->getID();
                $params['customer_name'] = $customerObject->getNameFormatted();
                return $this->render('GistSalesReportBundle:CustomerLayered:transactions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getCustomerTransactionsData($date_from, $date_to, $customer_id)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $allTransactions = $layeredReportService->getTransactions($date_from, $date_to, null, null);//$em->getRepository('GistPOSERPBundle:POSTransaction')->findBy(['customer'=>$customer_id]);

        foreach ($allTransactions as $transaction) {
            if (!$transaction->hasChildLayeredReport() && $transaction->getCustomer()->getID() == $customer_id) {
                $transactionId = $transaction->getTransDisplayIdFormatted();
                $totalSales = 0;
                $totalCost = 0;
                $transactionItems = $transaction->getItems();

                //loop items and check if item's brand is the current loop's brand then add the cost
                foreach ($transactionItems as $transactionItem) {
                    if (!$transactionItem->getReturned()) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }

                $brandTotalProfit = $totalSales - $totalCost;
                $customerObject = $em->getRepository('GistCustomerBundle:Customer')->findOneById($customer_id);
                if ($totalSales > 0) {
                    $list_opts[] = array(
                        'date_from' => $date_from,
                        'date_to' => $date_to,
                        'transaction_pos_name' => $transaction->getPOSLocation()->getName(),
                        'transaction_date' => $transaction->getDateCreate()->format('F d, Y h:i A'),
                        'transaction_id' => $transactionId,
                        'transaction_system_id' => $transaction->getID(),
                        'customer_name' => $customerObject->getNameFormatted(),
                        'customer_display_id' => $customerObject->getDisplayID(),
                        'customer_id' => $customerObject->getID(),
                        'total_sales' => number_format($totalSales, 2, '.', ','),
                        'total_cost' => number_format($totalCost, 2, '.', ','),
                        'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
                    );
                }
            }
        }

        if (count($allTransactions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END AREAS/L3
    public function viewTransactionDetailsAction($date_from, $date_to, $id, $customer_id)
    {
        //$this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
       // $params = $this->getViewParams('List');
        $obj = $em->getRepository('GistPOSERPBundle:POSTransaction')->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['customer_name'] = $obj->getCustomer()->getNameFormatted();
        $params['customer_creator'] = $obj->getCustomer()->getUserCreate()->getName();
        $params['customer'] = $obj->getCustomer();
        $params['readonly'] = true;

        if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
            $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
            $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
            $params['date_from'] = $date_from->format("m/d/Y");
            $params['date_to'] = $date_to->format("m/d/Y");
            $params['data'] = $this->getCustomerTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $customer_id);
            $params['date_from_url'] = $date_from->format("m-d-Y");
            $params['date_to_url'] = $date_to->format("m-d-Y");
            $customerObject = $em->getRepository('GistCustomerBundle:Customer')->findOneById($customer_id);
            $params['customer_id'] = $customerObject->getID();
            $params['customer_name'] = $customerObject->getNameFormatted();
            return $this->render('GistSalesReportBundle:CustomerLayered:transaction_details.html.twig', $params);

        } else {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getPOSData($date_from, $date_to, $region, $area)
    {
        $em = $this->getDoctrine()->getManager();
        $regionObject = $em->getRepository('GistLocationBundle:Regions')->findOneById($region);

        $allPOS = $em->getRepository('GistLocationBundle:POSLocations')->findBy([
            'area' => $area,
            'region' => $region
        ]);

        //$regionObject = $em->getRepository('GistLocationBundle:POSLocations')->findOneById($region);
        $areaObject = $em->getRepository('GistLocationBundle:Areas')->findOneById($area);

        foreach ($allPOS as $POSObject) {
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
                    if ($pos_loc->getRegion()->getID() == $region && $pos_loc->getArea()->getID() == $area) {
                        $totalSales += $transactionItem->getTotalAmount();
                    }
                }
            }

            $totalProfit = $totalSales - $totalCost;

            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'pos_loc_id' => $POSObject->getID(),
                    'region_id' => $region,
                    'area_id' => $area,
                    'pos_name' => $POSObject->getName(),
                    'region_name' => $regionObject->getName(),
                    'area_name' => $areaObject->getName(),
                    'total_sales' => number_format($totalSales, 2, '.', ','),
                    'total_cost' => number_format($totalCost, 2, '.', ','),
                    'total_profit' => number_format($totalProfit, 2, '.', ','),
                );
            }
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
