<?php

namespace Gist\ReportBundle\Model;

use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\BaseController;
use Gist\InventoryBundle\Entity\Warehouse;
use Gist\ChartBundle\Model\DateAggregate;
use Gist\ChartBundle\Model\Chart;
use Gist\ChartBundle\Model\ChartSeries;
use DateTime;
use DateInterval;

abstract class SummaryController extends BaseController 
{
    protected $filter_url;
    protected $menu_url;
    protected $title;
    protected $series_title;

    protected $method_date;
    protected $method_amount;

    public function indexAction()
    {
        $date = new DateTime();

        $params = [
            'interval' => 'daily',
            'date_from' => $date->format('Ymd'),
            'date_to' => $date->format('Ymd'),
            'wh_id' => 0
        ];

        return $this->redirect($this->generateUrl($this->filter_url, $params));
    }

    public function filterAction($interval, $date_from, $date_to, $wh_id)
    {
        $inv = $this->get('gist_inventory');

        // dates
        $dfrom = new DateTime($date_from . 'T00:00:00');
        $dto = new DateTime($date_to . 'T23:59:59');

        // shopfront  warehouses
        $wh_opts = $inv->getWarehouseOptions(['flag_shopfront' => true]);
        $wh_opts = array_reverse($wh_opts, true);
        $wh_opts[0] = 'All Branches';
        $wh_opts = array_reverse($wh_opts, true);

        // intervals
        $int_opts = [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
        
        $chart = $this->processAggregate($interval, $wh_id, $dfrom, $dto, $data);

        // params
        $params = $this->getViewParams('', $this->menu_url);
        $params['title'] = $this->title;
        $params['date_from'] = $dfrom;
        $params['date_to'] = $dto;
        $params['wh_opts'] = $wh_opts;
        $params['wh_id'] = $wh_id;
        $params['int_opts'] = $int_opts;
        $params['interval'] = $interval;
        $params['chart'] = $chart;
        $params['series_title'] = $this->series_title;
        $params['filter_url'] = $this->filter_url;
        $params['data'] = $data;

        return $this->render($this->getViewTemplate(), $params);
    }

    protected function getViewTemplate()
    {
        // return 'LeatherReportBundle:ReportSummary:index.html.twig';
    }

    protected function processAggregate($interval, $wh_id, $date_from, $date_to, &$return_data)
    {
        $data = $this->fetchData($wh_id, $date_from, $date_to);
        $return_data = $data;
            

        $ag_sum = new DateAggregate(new Chart());
        $ag_sum->processSeries(
            new ChartSeries($this->series_title),
            $interval,
            $data,
            $date_from,
            $date_to,
            $this->method_date,
            $this->method_amount
        );

        return $ag_sum->getChart();
    }
    
    abstract protected function fetchData($wh_id, $date_from, $date_to);
}
