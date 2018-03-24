<?php

namespace Gist\SalesReportBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Gist\POSERPBundle\ModesOfPayment;
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
            $params['all_data'] = $this->getAllData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
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
    public function modesIndexAction($date_from = null, $date_to = null, $position = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['position'] = $position;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->getModesData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");



                return $this->render('GistSalesReportBundle:SalesLayered:modes.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }
        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getModesData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        //get all brands
        $allModes = ModesOfPayment::getModesOptions();

        foreach ($allModes as $modeId => $modeName) {
            //initiate totals
            $brandId = $modeId;
            $totalSales = 0;
            $totalCost = 0;
            $transactionIds = array();

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionPayments = $layeredReportService->getTransactionPayments($date_from, $date_to, null, null);
            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionPayments as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    $pos_loc = $em->getRepository('GistLocationBundle:POSLocations')->findOneById($payment->getTransaction()->getPOSLocation());

                    if ($payment->getType() == $modeName) {
                        //$totalCost += $product->getCost();
                        $totalSales += $payment->getAmount();
                        //store transaction id of item for use
                        //array_push($brandTransactionIds, $transactionItem->getTransaction()->getID());
                    }
                }
            }


            $brandTotalProfit = $totalSales - $totalCost;

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'name' => $modeName,
                'id' => $modeId,
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($brandTotalProfit, 2, '.',','),
            );
        }

        if (count($allModes) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END POSITIONS/L2
    //FOR EMPLOYEES/L3 / CASH TRANS
    public function cashTransactionsIndexAction($date_from = null, $date_to = null, $mode = null)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['position '] = $mode ;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->cashTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $mode);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");


                //$positionObject = $em->getRepository('GistUserBundle:Group')->findOneById($position);

                //$params['position_id'] = $positionObject->getID();
                $params['mode_name'] = $mode;

                return $this->render('GistSalesReportBundle:SalesLayered:cash_transactions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function cashTransactionsData($date_from, $date_to, $mode)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $transactions = $layeredReportService->getTransactions($date_from, $date_to, null, null);

        foreach ($transactions as $transaction) {
            $totalSales = 0;
            foreach ($transaction->getPayments() as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    $pos_loc = $em->getRepository('GistLocationBundle:POSLocations')->findOneById($payment->getTransaction()->getPOSLocation());

                    if ($payment->getType() == $mode) {
                        $totalSales += $payment->getAmount();

                    }
                }
            }

            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'transaction_display_id' => $transaction->getTransDisplayId(),
                    'transaction_id' => $transaction->getID(),
                    'pos_name' => $transaction->getPOSLocation()->getName(),
                    'trans_date' => $transaction->getDateCreateFormattedPOS(),
                    'total_sales' => number_format($totalSales, 2, '.', ',')
                );
            }
        }

        if (count($transactions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END CASH TRANS/L3
    //FOR L3 CHECK TYPES
    public function checkTypesIndexAction($date_from = null, $date_to = null)
    {
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->getCheckTypesData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                return $this->render('GistSalesReportBundle:SalesLayered:check_type.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getCheckTypesData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        $list_opts = [];
        $qb = $em->getRepository('GistPOSERPBundle:POSTransactionPayment')->createQueryBuilder('a')->groupBy('a.check_type');

        $allCheckTypes = $qb->getQuery()->getResult();

        foreach ($allCheckTypes as $type) {
            $totalSales = 0;

            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionPayments = $layeredReportService->getTransactionPayments($date_from, $date_to, null, null);
            foreach ($transactionPayments as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    if ($payment->getCheckType() == $type->getCheckType() && $payment->getType() == 'Check') {
                        $totalSales += $payment->getAmount();
                    }
                }
            }

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'type' => $type->getCheckType(),
                'total_sales' => number_format($totalSales, 2, '.',',')
            );
        }

        if (count($allCheckTypes) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END L3 CHECKTYPES
    //FOR L3 CHECK TYPES
    public function terminalsIndexAction($date_from = null, $date_to = null)
    {
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->getTerminalsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                return $this->render('GistSalesReportBundle:SalesLayered:terminals.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getTerminalsData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        $list_opts = [];
        $qb = $em->getRepository('GistPOSERPBundle:POSTransactionPayment')->createQueryBuilder('a')->groupBy('a.card_terminal_operator');

        $allCheckTypes = $qb->getQuery()->getResult();

        foreach ($allCheckTypes as $type) {
            $totalSales = 0;

            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionPayments = $layeredReportService->getTransactionPayments($date_from, $date_to, null, null);
            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionPayments as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    if ($payment->getCardTerminalOperator() == $type->getCardTerminalOperator() && $payment->getType() == 'Credit Card') {
                        $totalSales += $payment->getAmount();
                    }
                }
            }

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'type' => $type->getCardTerminalOperator(),
                'total_sales' => number_format($totalSales, 2, '.',',')
            );
        }

        if (count($allCheckTypes) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }

    //FOR L4 / CARD TRANS
    public function cardTransactionsIndexAction($date_from = null, $date_to = null, $terminal = null)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['terminal'] = $terminal ;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->cardTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $terminal);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");


                //$positionObject = $em->getRepository('GistUserBundle:Group')->findOneById($position);

                //$params['position_id'] = $positionObject->getID();
                $params['terminal_name'] = $terminal;

                return $this->render('GistSalesReportBundle:SalesLayered:card_transactions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function cardTransactionsData($date_from, $date_to, $terminal)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $transactions = $layeredReportService->getTransactions($date_from, $date_to, null, null);

        foreach ($transactions as $transaction) {
            $totalSales = 0;
            foreach ($transaction->getPayments() as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    if ($payment->getCardTerminalOperator() == $terminal) {
                        $totalSales += $payment->getAmount();
                    }
                }
            }
            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'transaction_display_id' => $transaction->getTransDisplayId(),
                    'pos_name' => $transaction->getPOSLocation()->getName(),
                    'trans_date' => $transaction->getDateCreateFormattedPOS(),
                    'transaction_id' => $transaction->getID(),
                    'total_sales' => number_format($totalSales, 2, '.', ',')
                );
            }
        }

        if (count($transactions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END CARD TRANS/L4

    //FOR L4 / CHECK TRANS
    public function checkTransactionsIndexAction($date_from = null, $date_to = null, $check_type = null)
    {
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_sales';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            $params['check_type'] = $check_type;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->checkTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $check_type);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                return $this->render('GistSalesReportBundle:SalesLayered:check_transactions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }
        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function checkTransactionsData($date_from, $date_to, $check_type)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $transactions = $layeredReportService->getTransactions($date_from, $date_to, null, null);

        foreach ($transactions as $transaction) {
            $totalSales = 0;
            foreach ($transaction->getPayments() as $payment) {
                if (!$payment->getTransaction()->hasChildLayeredReport()) {
                    if ($payment->getCheckType() == $check_type) {
                        $totalSales += $payment->getAmount();
                    }
                }
            }
            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'transaction_display_id' => $transaction->getTransDisplayId(),
                    'pos_name' => $transaction->getPOSLocation()->getName(),
                    'trans_date' => $transaction->getDateCreateFormattedPOS(),
                    'transaction_id' => $transaction->getID(),
                    'total_sales' => number_format($totalSales, 2, '.', ',')
                );
            }
        }

        if (count($transactions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END CARD TRANS/L4








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

