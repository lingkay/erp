<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class BalanceController extends CrudController{
    
    public function __construct()
    {
        $this->route_prefix = 'gist_report_dr';
        $this->title = 'Balances Report';

        $this->list_title = 'Balances Report';
        $this->list_type = 'static';
    }
    
    public function indexAction() {
        $this->title = 'Balances Report';
        $params = $this->getViewParams('', 'gist_report_balance_summary');
        $inv = $this->get('gist_inventory');
        
        $this->print = 'gist_report_balance_print';
        $this->csv = 'gist_report_balance_csv';
        
        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['title'] = $this->title;
        $params['print'] = $this->print;
        $params['csv'] = $this->csv;
        $params['br_opts'] = $inv->getBranchOptions();
        $params['prod_opts'] = $inv->getProductGroupOptions();
        
        return $this->render('GistReportBundle:Balance:index.html.twig', $params);
    }
    
    public function headers()
    {
        // csv headers
        $headers = [
            'Receipt #',
            'Receipt Date',            
            'Delivery Date',
            'Company Name',
            'Terms',
            'Balance Due',
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

        $this->title = 'Balance Report';
        $params = $this->getViewParams('', 'gist_report_balance_summary');

//        $params['grid_cols'] = $this->headers();
//        $params['data'] = $data;

        return $this->render(
            'GistReportBundle:Balance:print.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

}
