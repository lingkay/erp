<?php

namespace Gist\SalesReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class SalesOrderReportController extends CrudController{
    
    public function __construct()
    {
        $this->route_prefix = 'gist_sales_report_sales_order';
        $this->title = 'Sales Orders';

        $this->list_title = 'Sales Orders Report';
        $this->list_type = 'static';
    }
    
    public function indexAction() {
        $this->title = 'Sales Orders Report';
        $params = $this->getViewParams('', 'gist_sales_report_sales_order_summary');
        $inv = $this->get('gist_inventory');
        
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['title'] = $this->title;
        $params['br_opts'] = null;
        $params['prod_opts'] = $inv->getProductGroupOptions();
        
        return $this->render('GistSalesReportBundle:SalesOrders:index.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

}
