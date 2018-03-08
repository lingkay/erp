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
        } else {
            if ($date_from != null) {
                $date_from = DateTime::createFromFormat('m-d-Y', $date_from);
                $date_to = DateTime::createFromFormat('m-d-Y', $date_to);
                $date_from_twig = $date_from->format("m/d/Y");
                $date_to_twig = $date_to->format("m/d/Y");
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

        return [
            'total_sales' => number_format($total_payments, 2, '.',','),
            'total_cost' => number_format($total_cost, 2, '.',','),
            'total_profit' => number_format($total_profit, 2, '.',','),
            'quantity_sold' => $quantitySold
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
                        //store transaction id of item for use
                        array_push($brandTransactionIds, $transactionItem->getTransaction()->getID());
                        $quantitySold++;
                    }
                }
            }

            $brandTotalProfit = $brandTotalSales - $brandTotalCost;

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'brand_id' => $brandObject->getID(),
                'brand_name' => $brandObject->getName(),
                'total_sales' => number_format($brandTotalSales, 2, '.',','),
                'total_cost' => number_format($brandTotalCost, 2, '.',','),
                'total_profit' => number_format($brandTotalProfit, 2, '.',','),
                'quantity_sold' => $quantitySold
            );
        }

        return $list_opts;
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

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'brand_id' => $brand,
                'category_id' => $categoryObject->getID(),
                'brand_name' => $brandObject->getName(),
                'category_name' => $categoryObject->getName(),
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($totalProfit, 2, '.',','),
                'quantity_sold' => $quantitySold
            );
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
        //get all categories
        $allProducts = $em->getRepository('GistInventoryBundle:Product')->findBy([
            'category' => $category,
            'brand' => $brand
        ]);

        $brandObject = $em->getRepository('GistInventoryBundle:Brand')->findOneById($brand);
        $categoryObject = $em->getRepository('GistInventoryBundle:ProductCategory')->findOneById($category);

        foreach ($allProducts as $productObject) {
            //initiate totals
            $productId = $productObject->getID();
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
                    if ($product->getCategory()->getID() == $category && $product->getBrand()->getID() == $brand && $product->getID() == $productId) {
                        $totalCost += $product->getCost();
                        $totalSales += $transactionItem->getTotalAmount();
                        $quantitySold++;
                    }
                }
            }

            $totalProfit = $totalSales - $totalCost;

            $list_opts[] = array(
                'date_from'=>$date_from,
                'date_to'=> $date_to,
                'product_id' => $productObject->getID(),
                'brand_id' => $brand,
                'category_id' => $category,
                'product_name' => $productObject->getName(),
                'brand_name' => $brandObject->getName(),
                'category_name' => $categoryObject->getName(),
                'total_sales' => number_format($totalSales, 2, '.',','),
                'total_cost' => number_format($totalCost, 2, '.',','),
                'total_profit' => number_format($totalProfit, 2, '.',','),
                'quantity_sold' => $quantitySold
            );
        }

        if (count($allProducts) > 0) {
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

