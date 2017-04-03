<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ReportBundle\Model\ReportPDF;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class PoController extends CrudController{
    
    public function __construct()
    {
        $this->route_prefix = 'gist_report_po';
        $this->title = 'Purchase Order Report';

        $this->list_title = 'Purchase Order Report';
        $this->list_type = 'static';
    }
    
    public function indexAction() {
        $this->title = 'Purchase Order Report';
        $params = $this->getViewParams('', 'gist_report_po_summary');
        $inv = $this->get('gist_inventory');
        
        $this->print = 'gist_report_po_print';
        $this->csv = 'gist_report_po_csv';
        
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
//        $params['filter'] = $this->filter;
        
        return $this->render('GistReportBundle:InternalPo:index.html.twig', $params);
    }
   
    public function headers()
    {
        // csv headers
        $headers = [
            'P.O. Receipt #',
            'P.O Date Created',            
            'Target Delivery Date',
            'Total Item',
            'Total Qty',
            'Amt. Value',
            'Created By',           
        ];
        return $headers;
    }
    
    public function csvAction(){
        // fetch data
//        $data = $this->fetchData($date_from, $date_to);

        // filename generate
        $date = new DateTime();
        $filename = $date->format('Ymdis') . '.csv';

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

        // data
//        $i=0;
//        foreach ($data as $so)
//        {
//
//            // build data
//            $arr_data = [
//                $so->getCode(),
//                $so->getAssignedUserstext(),
//                $so->getDateIssue()->format('m/d/Y'),
//                $so->getServicesText(),
//                $so->getCustomer()->getFullName(),
//                $so->getCustomer()->getCity(),
//                $so->getNote(),
//                $so->getStatus(),
//            ];
//
//            $i++;
            // output csv
//            fputcsv($file, $arr_data);
//        }
        fclose($file);
//        $csv = ob_get_contents();
//        ob_end_clean();


        // csv header
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
//        $response->setContent($csv);

        return $response;
    }       
    
    public function printAction()
    {       

        // fetch data
//        $data = $this->fetchData($date_from, $date_to);

        $this->title = 'Purchase Order Report';
        $params = $this->getViewParams('', 'gist_report_po_summary');

//        $params['grid_cols'] = $this->headers();
//        $params['data'] = $data;

        return $this->render(
            'GistReportBundle:InternalPo:print.html.twig', $params);
    }

    protected function getObjectLabel($object) {
        
    }

    protected function newBaseClass() {
        
    }

    public function reportAction()
    {
        $report = new ReportPDF();
        $report->generate();
    }

}
