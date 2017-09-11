<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class InventoryStockController extends CrudController{
    
    public function __construct()
    {
        $this->route_prefix = 'gist_report_stock';
        $this->title = 'Inventory Stock';

        $this->list_title = 'Inventory Stock';
        $this->list_type = 'static';
    }
    
    public function indexAction() {
        $this->title = 'Inventory Stock';
        $params = $this->getViewParams('', 'gist_report_stock_summary');
        $inv = $this->get('gist_inventory');
        
        $this->print = 'gist_report_stock_print';
        $this->csv = 'gist_report_stock_csv';
        
        $params['title'] = $this->title;
        $params['print'] = $this->print;
        $params['csv'] = $this->csv;
        $params['br_opts'] = $inv->getBranchOptions();
        $params['item_opts'] = $inv->getProductOptions();
        $params['prod_opts'] = $inv->getProductGroupOptions();
        
        return $this->render('GistReportBundle:InventoryStock:index.html.twig', $params);
    }
    
    public function headers()
    {
        // csv headers
        $headers = [
            'Item Code',
            'Description',            
            'Specs',
            'Qty',
            'Unit Price',
            'Total Price',
        ];
        return $headers;
    }
    
    public function csvAction(){

        // filename generate
        $date = new DateTime();
        $filename = $date->format('Ymdis') . '.csv';

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

        fclose($file);


        // csv header
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }       
    
    public function printAction()
    {       

        // fetch data
//        $data = $this->fetchData($date_from, $date_to);

        $this->title = 'Inventory Stock Report';
        $params = $this->getViewParams('', 'gist_report_stock_summary');

//        $params['grid_cols'] = $this->headers();
//        $params['data'] = $data;

        return $this->render(
            'GistReportBundle:InventoryStock:print.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

}
