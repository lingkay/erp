<?php

namespace Gist\ReportBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use DateTime;


class MandatoryReportController extends BaseController
{
    public function indexAction($date_from = null , $date_to = null, $visit = null)
    {
        $this->title = 'Mandatory Report';
        $this->print = 'gist_report_mandatory_print';
        $this->csv = 'gist_report_mandatory_csv';
        $this->filter = 'gist_report_mandatory_summary_filter';

        // get params
        $params = $this->getViewParams('', 'gist_report_mandatory_summary');

        $date_from = new DateTime($date_from);
        $date_to = new DateTime($date_to);
        $date_from = $date_from->format("Y-m-d");
        $date_to = $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to, $visit);

        $params['visit'] = $visit;

        if ($visit == null)
        {

        $params['visit'] = 0;
        }

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['title'] = $this->title;
        $params['print'] = $this->print;
        $params['csv'] = $this->csv;
        $params['filter'] = $this->filter;
        $params['visit_opts'] = [
            '0' => 'Select Filter:',
            '1' => 'Under Default Visit',
            '2' => 'Equal to Default Visit',
            '3' => 'Above Normal Visit'
        ];

        return $this->render('GistReportBundle:MandatoryReport:index.html.twig', $params);
    }


    protected function padFormParams(&$params, $date_from, $date_to, $visit)
    {
        $em = $this->getDoctrine()->getManager();
        $params['sc'] = $this->fetchData($date_from, $date_to,$visit);
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
            $grid->newColumn('City', 'getCity', 'user_id', 'c' ),
            $grid->newColumn('Remarks', 'getNote', 'note' ),
            $grid->newColumn('Status', 'getStatus', 'status' ),
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
            'City',
            'Status',
        ];
        return $headers;
    }


    public function printAction($date_from, $date_to, $visit)
    {       

        // fetch data
        $data = $this->fetchData($date_from, $date_to, $visit);

        $this->title = 'Mandatory Report';
        $params = $this->getViewParams('', 'gist_report_mandatory_summary');

        $params['grid_cols'] = $this->headers();
        $params['data'] = $data;

        return $this->render(
            'GistReportBundle:MandatoryReport:print.html.twig', $params);
    }   

    public function csvAction($date_from, $date_to ,$visit)
    {
        // fetch data
        $data = $this->fetchData($date_from, $date_to, $visit);

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
        foreach ($data as $sv)
        {

            // build data
            $arr_data = [
                $sv->getServiceOrder()->getCode(),
                $sv->getAssignedUser()->getName(),
                $sv->getServiceOrder()->getDateIssue()->format('m/d/Y'),
                $sv->getProduct()->getName(),
                $sv->getServiceOrder()->getCustomer()->getFullName(),
                $sv->getServiceOrder()->getCustomer()->getCity(),
                $sv->getServiceOrder()->getStatus(),
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


    protected function fetchData($date_from, $date_to, $visit = null)
    {
        $em = $this->getDoctrine()->getManager();

        if ($visit == 1)
        { 
            $query = 'select sv from GistServiceBundle:SVEntry sv join sv.service_order so where so.date_issue >= :date_from and so.date_issue <= :date_to and sv.default_visit > sv.number_of_visits';
        }

        elseif ($visit == 2)
        {
            $query = 'select sv from GistServiceBundle:SVEntry sv join sv.service_order so where so.date_issue >= :date_from and so.date_issue <= :date_to and sv.default_visit = sv.number_of_visits';
        }

        elseif ($visit == 3)
        {
            $query = 'select sv from GistServiceBundle:SVEntry sv join sv.service_order so where so.date_issue >= :date_from and so.date_issue <= :date_to and sv.default_visit < sv.number_of_visits';
        }
        else
        {
            $query = 'select sv from GistServiceBundle:SVEntry sv join sv.service_order so where so.date_issue >= :date_from and so.date_issue <= :date_to';            
        }


        $data = $em->createQuery($query)
            ->setParameter('date_from', $date_from)
            ->setParameter('date_to', $date_to)
            ->getResult();

        return $data;
    }




}
