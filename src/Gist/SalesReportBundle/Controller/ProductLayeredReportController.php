<?php

namespace Gist\SalesReportBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\TemplateBundle\Model\RouteGenerator as RouteGenerator;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use DateTime;

class ProductLayeredReportController extends Controller
{
    protected $repo;
    protected $base_view;
    protected $route_gen;

    public function __construct()
    {
        $this->route_prefix = 'gist_layered_sales_report_product';
        $this->title = 'Layered Report - Product';

        $this->list_title = 'Layered Report - Product';
        $this->list_type = 'static';
    }

    // FOR TOP LAYER
    public function indexAction($date_from = null, $date_to = null, $brand = null, $category = null)
    {
        $data = $this->getRequest()->request->all();
        $this->route_prefix = 'gist_layered_sales_report_product';
        $params = $this->getViewParams('List');
        $this->getControllerBase();

        //PARAMS
        $params['brand'] = $brand;
        $params['category'] = $category;

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

        return $this->render('GistSalesReportBundle:ProductLayered:index.html.twig', $params);
    }

    protected function getAllData($date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $data = $layeredReportService->getTransactions($date_from, $date_to, null, null);

        $total_payments = 0;
        $total_cost = 0;
        $total_profit = 0;
        $quantitySold = 0;

        foreach ($data as $d) {
            if (!$d->hasChildLayeredReport()) {
                $total_payments += $d->getTransactionTotal();
                foreach ($d->getItems() as $item) {
                    if (!$item->getTransaction()->hasChildLayeredReport() && !$item->getReturned()) {
                        $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($item->getProductId());
                        $total_cost += $product->getCost();
                        $quantitySold++;
                    }
                }
            }
        }

        $total_profit = $total_payments - $total_cost;
        if ($total_payments == 0) {
            $profit_percentage = ($total_profit / 1) * 100;
        } else {
            $profit_percentage = ($total_profit / $total_payments) * 100;
        }

        return [
            'total_sales' => number_format($total_payments, 2, '.',','),
            'total_cost' => number_format($total_cost, 2, '.',','),
            'total_profit' => number_format($total_profit, 2, '.',','),
            'quantity_sold' => $quantitySold,
            'profit_percentage' => number_format($profit_percentage, 2, '.',',')
        ];
    }
    //END TOP LAYER

    //FOR BRANDS/L2
    public function brandsIndexAction($date_from = null, $date_to = null, $brand = null, $category = null)
    {
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_product';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['brand'] = $brand;
            $params['category'] = $category;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['brands_data'] = $this->getBrandsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'));
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                return $this->render('GistSalesReportBundle:ProductLayered:brands.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }
        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getBrandsData($date_from, $date_to)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        //get all brands
        $allBrands = $em->getRepository('GistInventoryBundle:Brand')->findAll();

        foreach ($allBrands as $brandObject) {
            //initiate totals
            $brandId = $brandObject->getID();
            $brandTotalSales = 0;
            $brandTotalCost = 0;
            $quantitySold = 0;
            $brandTransactionIds = array();

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($transactionItem->getProductId());
                    if ($product->getBrand()->getID() == $brandId) {
                        $brandTotalCost += $product->getCost();
                        $brandTotalSales += $transactionItem->getTotalAmount();
                        array_push($brandTransactionIds, $transactionItem->getTransaction()->getID());
                        $quantitySold++;
                    }
                }
            }

            $brandTotalProfit = $brandTotalSales - $brandTotalCost;
            if ($brandTotalSales < 1) {
                $profit_percentage = ($brandTotalProfit / 1) * 100;
            } else {
                $profit_percentage = ($brandTotalProfit / $brandTotalSales) * 100;
            }

            if ($brandTotalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'brand_id' => $brandObject->getID(),
                    'brand_name' => $brandObject->getName(),
                    'total_sales' => number_format($brandTotalSales, 2, '.', ','),
                    'total_cost' => number_format($brandTotalCost, 2, '.', ','),
                    'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
                    'quantity_sold' => $quantitySold,
                    'profit_percentage' => number_format($profit_percentage, 2, '.', ',')
                );
            }
        }

        if (count($allBrands) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END BRANDS/L2

    //FOR BRANDED/L3 / SHOW BRAND CATEGORIES
    public function brandedIndexAction($date_from = null, $date_to = null, $brand = null, $category = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_product';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['brand'] = $brand;
            $params['category'] = $category;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['categories_data'] = $this->getCategoriesData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'),$brand);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);

                $params['brand_id'] = $brandObject->getID();
                $params['brand_name'] = $brandObject->getName();

                return $this->render('GistSalesReportBundle:ProductLayered:categories.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getCategoriesData($date_from, $date_to, $brand)
    {
        $em = $this->getDoctrine()->getManager();
        //get all categories
        $allCategories = $em->getRepository('GistInventoryBundle:ProductCategory')->findAll();
        $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);

        foreach ($allCategories as $categoryObject) {
            //initiate totals
            $categoryId = $categoryObject->getID();
            $totalSales = 0;
            $totalCost = 0;
            $quantitySold = 0;

            //get all transaction items based on date filter
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($transactionItem->getProductId());
                    if ($product->getCategory()->getID() == $categoryId && $product->getBrand()->getID() == $brand) {
                        $totalCost += $product->getCost();
                        $totalSales += $transactionItem->getTotalAmount();
                        $quantitySold++;
                    }
                }
            }

            $totalProfit = $totalSales - $totalCost;
            if ($totalSales < 1) {
                $profit_percentage = ($totalProfit / 0.001) * 100;
            } else {
                $profit_percentage = ($totalProfit / $totalSales) * 100;
            }

            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'brand_id' => $brand,
                    'category_id' => $categoryObject->getID(),
                    'brand_name' => $brandObject->getName(),
                    'category_name' => $categoryObject->getName(),
                    'total_sales' => number_format($totalSales, 2, '.', ','),
                    'total_cost' => number_format($totalCost, 2, '.', ','),
                    'total_profit' => number_format($totalProfit, 2, '.', ','),
                    'quantity_sold' => $quantitySold,
                    'profit_percentage' => number_format($profit_percentage, 2, '.', ',')
                );
            }
        }

        if (count($allCategories) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }
    //END BRANDED/L3

    //FOR CATEGORIZED/L4 / SHOW PRODUCTS
    public function categorizedIndexAction($date_from = null, $date_to = null, $brand = null, $category = null)
    {
        $em = $this->getDoctrine()->getManager();

        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_product';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            //PARAMS
            $params['brand'] = $brand;
            $params['category'] = $category;

            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['products_data'] = $this->getProductsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'),$brand, $category);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");

                $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
                $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);

                $params['brand_id'] = $brandObject->getID();
                $params['brand_name'] = $brandObject->getName();
                $params['category_id'] = $categoryObject->getID();
                $params['category_name'] = $categoryObject->getName();

                return $this->render('GistSalesReportBundle:ProductLayered:products.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getProductsData($date_from, $date_to, $brand, $category)
    {
        $em = $this->getDoctrine()->getManager();

        $allProducts = $em->getRepository('GistInventoryBundle:Product')->findBy([
            'category' => $category,
            'brand' => $brand
        ]);

        $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
        $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);

        foreach ($allProducts as $productObject) {
            $productId = $productObject->getID();
            $totalSales = 0;
            $totalCost = 0;
            $quantitySold = 0;
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

            //loop items and check if item's brand is the current loop's brand then add the cost
            foreach ($transactionItems as $transactionItem) {
                if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                    $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($transactionItem->getProductId());
                    if ($product->getCategory()->getID() == $category && $product->getBrand()->getID() == $brand && $product->getID() == $productId) {
                        $totalCost += $product->getCost();
                        $totalSales += $transactionItem->getTotalAmount();
                        $quantitySold++;
                    }
                }
            }

            $totalProfit = $totalSales - $totalCost;
            if ($totalSales < 1) {
                $profit_percentage = ($totalProfit / 1) * 100;
            } else {
                $profit_percentage = ($totalProfit / $totalSales) * 100;
            }

            if ($totalSales > 0) {
                $list_opts[] = array(
                    'date_from' => $date_from,
                    'date_to' => $date_to,
                    'product_id' => $productObject->getID(),
                    'brand_id' => $brand,
                    'category_id' => $category,
                    'product_name' => $productObject->getName(),
                    'brand_name' => $brandObject->getName(),
                    'category_name' => $categoryObject->getName(),
                    'total_sales' => number_format($totalSales, 2, '.', ','),
                    'total_cost' => number_format($totalCost, 2, '.', ','),
                    'total_profit' => number_format($totalProfit, 2, '.', ','),
                    'quantity_sold' => $quantitySold,
                    'profit_percentage' => number_format($profit_percentage, 2, '.', ',')
                );
            }
        }

        if (count($allProducts) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }

    //LAST NEW LAYER
    public function transactionsIndexAction($date_from = null, $date_to = null, $brand = null, $category = null, $product_id = null)
    {
        $em = $this->getDoctrine()->getManager();
        try {
            $data = $this->getRequest()->request->all();
            $this->route_prefix = 'gist_layered_sales_report_product';
            $params = $this->getViewParams('List');
            $this->getControllerBase();

            $params['product_id'] = $product_id ;
            if (DateTime::createFromFormat('m-d-Y', $date_from) !== false && DateTime::createFromFormat('m-d-Y', $date_to) !== false) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $params['date_from'] = $date_from->format("m/d/Y");
                $params['date_to'] = $date_to->format("m/d/Y");
                $params['data'] = $this->getProductTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $brand, $category, $product_id);
                $params['date_from_url'] = $date_from->format("m-d-Y");
                $params['date_to_url'] = $date_to->format("m-d-Y");
                $productObject = $em->getRepository('GistInventoryBundle:Product')->findOneById($product_id);
                $params['product_id'] = $productObject->getID();
                $params['product_name'] = $productObject->getName();
                $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
                $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);
                $params['brand_id'] = $brandObject->getID();
                $params['brand_name'] = $brandObject->getName();
                $params['category_id'] = $categoryObject->getID();
                $params['category_name'] = $categoryObject->getName();
                return $this->render('GistSalesReportBundle:ProductLayered:transactions.html.twig', $params);

            } else {
                return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
            }


        } catch (Exception $e) {
            return $this->redirect($this->generateUrl('gist_layered_sales_report_product_index'));
        }
    }

    protected function getProductTransactionsData($date_from, $date_to, $brand, $category, $product_id)
    {
        $list_opts = [];
        $em = $this->getDoctrine()->getManager();
        $layeredReportService = $this->get('gist_layered_report_service');
        $allTransactions = $layeredReportService->getTransactions($date_from, $date_to, null, null);
        $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
        $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);
        $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

        $transIds = [];
        foreach ($transactionItems as $transactionItem) {
            if (!$transactionItem->getTransaction()->hasChildLayeredReport() && !$transactionItem->getReturned()) {
                $product = $em->getRepository('GistInventoryBundle:Product')->findOneById($transactionItem->getProductId());
                if ($product->getCategory()->getID() == $category && $product->getBrand()->getID() == $brand && $product->getID() == $product_id) {
                    array_push($transIds, $transactionItem->getTransaction()->getID());
                }
            }
        }

        $transIds = array_unique($transIds);
        foreach ($allTransactions as $transaction) {
            if (in_array($transaction->getID(), $transIds)) {
                if (!$transaction->hasChildLayeredReport()) {
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
                    $productObject = $em->getRepository('GistInventoryBundle:Product')->findOneById($product_id);
                    if ($totalSales > 0) {
                        $list_opts[] = array(
                            'date_from' => $date_from,
                            'date_to' => $date_to,
                            'transaction_pos_name' => $transaction->getPOSLocation()->getName(),
                            'transaction_date' => $transaction->getDateCreate()->format('F d, Y h:i A'),
                            'transaction_id' => $transactionId,
                            'transaction_system_id' => $transaction->getID(),
                            'product_name' => $productObject->getName(),
                            'product_id' => $productObject->getID(),
                            'total_sales' => number_format($totalSales, 2, '.', ','),
                            'total_cost' => number_format($totalCost, 2, '.', ','),
                            'total_profit' => number_format($brandTotalProfit, 2, '.', ','),
                            'brand_id' => $brandObject->getID(),
                            'category_id' => $categoryObject->getID()
                        );
                    }
                }
            }
        }

        if (count($allTransactions) > 0) {
            return $list_opts;
        } else {
            return null;
        }
    }

    public function viewTransactionDetailsAction($date_from, $date_to, $id, $brand, $category, $product_id)
    {
        $em = $this->getDoctrine()->getManager();
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
            $params['data'] = $this->getProductTransactionsData($date_from->format('Y-m-d'), $date_to->format('Y-m-d'), $brand, $category, $product_id);
            $params['date_from_url'] = $date_from->format("m-d-Y");
            $params['date_to_url'] = $date_to->format("m-d-Y");

            $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
            $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);
            $productObject = $em->getRepository('GistInventoryBundle:Product')->findOneById($product_id);
            $params['product_id'] = $productObject->getID();
            $params['product_name'] = $productObject->getName();
            $params['brand_id'] = $brandObject->getID();
            $params['brand_name'] = $brandObject->getName();
            $params['category_id'] = $categoryObject->getID();
            $params['category_name'] = $categoryObject->getName();

            return $this->render('GistSalesReportBundle:ProductLayered:transaction_details.html.twig', $params);

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

        $areaObject = $em->getRepository('GistLocationBundle:Areas')->findOneById($area);

        foreach ($allPOS as $POSObject) {
            $productId = $POSObject->getID();
            $totalSales = 0;
            $totalCost = 0;
            $layeredReportService = $this->get('gist_layered_report_service');
            $transactionItems = $layeredReportService->getTransactionItems($date_from, $date_to, null, null);

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
    //END LAST NEW LAYER

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
