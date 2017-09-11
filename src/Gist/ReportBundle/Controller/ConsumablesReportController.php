<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use DateTime;


class ConsumablesReportController extends BaseController
{
    public function indexAction($date_from = null , $date_to = null)
    {
        $this->title = 'Product Consumables Report';
        $this->print = 'gist_report_consumables_print';
        $this->csv = 'gist_report_consumables_csv';
        $this->filter = 'gist_report_consumables_summary_filter';

        // get params
        $params = $this->getViewParams('', 'gist_report_consumables_summary');

        $date_from = new DateTime($date_from);
        $date_to = new DateTime($date_to);
        $date_from = $date_from->format("Y-m-d");
        $date_to = $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['title'] = $this->title;
        $params['print'] = $this->print;
        $params['csv'] = $this->csv;
        $params['filter'] = $this->filter;

        return $this->render('GistReportBundle:ProductGroupsReport:index.html.twig', $params);
    }


    protected function padFormParams(&$params, $date_from, $date_to)
    {
        $em = $this->getDoctrine()->getManager();
        $params['so'] = $this->fetchData($date_from, $date_to);
        $params['stock_cols'] = $this->getStockColumns();

        return $params;
    }


    protected function getStockColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Code', 'getCode', 'code' ),
            $grid->newColumn('Assigned To', 'getAssignedUsersText', 'user_id' ),
            $grid->newColumn('Date Issue', 'getDateIssue', 'date_issue' ),
            $grid->newColumn('Services', 'getServicesText', 'services' ),
            $grid->newColumn('Customer', 'getFullName', 'user_id', 'c' ),
            $grid->newColumn('Product', 'getCity', 'user_id', 'c' ),
            $grid->newColumn('Quantity', 'getNote', 'note' ),
        );
    }

    public function headers()
    {
        // csv headers
        $headers = [
            'Code',
            'Assiged To',            
            'Date_Issue',
            'Services',
            'Customer',
            'Product',
            'Quantity',
        ];
        return $headers;
    }


    public function printAction($date_from, $date_to)
    {       

        // fetch data
        $data = $this->fetchData($date_from, $date_to);

        $this->title = 'Product Consumables Report';
        $params = $this->getViewParams('', 'gist_report_equipment_summary');

        $params['grid_cols'] = $this->headers();
        $params['data'] = $data;

        return $this->render(
            'GistReportBundle:ProductGroupsReport:print.html.twig', $params);
    }   

    public function csvAction($date_from, $date_to)
    {
        // fetch data
        $data = $this->fetchData($date_from, $date_to);

        // filename generate
        $date = new DateTime();
        $filename = $date->format('Ymdis') . '.csv';

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

        // data
        $i=0;
        foreach ($data as $so)
        {
            // build data
            $arr_data = [
                $so->getServiceOrder()->getCode(),
                $so->getServiceOrder()->getAssignedUserstext(),
                $so->getServiceOrder()->getDateIssue()->format('m/d/Y'),
                $so->getServiceOrder()->getServicesText(),
                $so->getServiceOrder()->getCustomer()->getFullName(),
                $so->getProduct()->getName(),
                $so->getQuantity(),
            ];

            $i++;
            // output csv
            fputcsv($file, $arr_data);
        }
        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();


        // csv header
        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }


    protected function fetchData($date_from, $date_to)
    {

        $consumable_id = $this->get('gist_configuration')->get('gist_product_group_default');

        $em = $this->getDoctrine()->getManager();
        $query = 'select s from GistServiceBundle:SVConsumption s join s.product p join s.service_order o where o.date_issue >= :date_from and o.date_issue <= :date_to and p.prodgroup_id = :id';
        $data = $em->createQuery($query)
            ->setParameter('date_from', $date_from)
            ->setParameter('date_to', $date_to)
            ->setParameter('id', $consumable_id)
            ->getResult();

        return $data;
    }


}
