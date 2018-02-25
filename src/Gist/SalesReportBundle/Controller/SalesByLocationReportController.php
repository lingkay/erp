<?php

namespace Gist\SalesReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use DateTime;

class SalesByLocationReportController extends CrudController{

    public function __construct()
    {
        $this->route_prefix = 'gist_sales_report_sales_entries';
        $this->title = 'Price Adjustment Report';

        $this->list_title = 'Price Adjustment Report';
        $this->list_type = 'static';
    }

    public function indexAction() {
        $this->title = 'Sales Entries Report';
        $params = $this->getViewParams('', 'gist_sales_report_sales_entries_summary');
        $inv = $this->get('gist_inventory');

        $this->print = 'gist_report_prcadjust_print';
        $this->csv = 'gist_report_prcadjust_csv';

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

        $params['area_opts'] = $this->getAreaOptions();
        $params['brand_opts'] = $this->getBrandOptions();
        $params['pos_loc_opts'] = array('0'=>'Main Warehouse') + $inv->getPOSLocationTransferOptionsOnly();


        $params['status_opts'] = array(
            '1' => 'Active',
            '0' => 'Inactive'
        );


        return $this->render('GistSalesReportBundle:SalesEntries:index.html.twig', $params);
    }

    public function headers()
    {
        // csv headers
        $headers = [
            'Item Code',
            'Description',
            'Specs',
            'Old Original Price',
            'Update Original Price',
            'Date Update',
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

        $this->title = 'Price Adjustment Report';
        $params = $this->getViewParams('', 'gist_report_prcadjust_summary');

//        $params['grid_cols'] = $this->headers();
//        $params['data'] = $data;

        return $this->render(
            'GistSalesReportBundle:SalesEntries:print.html.twig', $params);
    }

    protected function getObjectLabel($object) {

    }

    protected function newBaseClass() {

    }

    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    public function getBrandOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistInventoryBundle:Brand',
            $filter,
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }


    public function getAreaOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistLocationBundle:Areas',
            $filter,
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

}
