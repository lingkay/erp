<?php

namespace Hris\ToolsBundle\Model;

use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use Gist\UserBundle\Entity\ItemsList;
use Doctrine\ORM\EntityManager;
use Gist\ChartBundle\Model\ChartSeries;
use Gist\ChartBundle\Model\Chart;
use Gist\ChartBundle\Model\DateAggregate;

use DateTime;
use DateInterval;

class XZManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    
    public function getSalesChart($data)
    {
        $dateFrom = new DateTime();
        $dateFrom->modify('-7 days');
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime();
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;
        $chart = $this->processAggregate($data);
        return $chart;
    }

    protected function getSalesData($data)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->select(["o.date_create as date_create",
    		"o.transaction_total as transaction_total"])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to']);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    protected function getSalesMetaData($data)
    {
    	$qb = $this->em->createQueryBuilder();
    	$qb->select(["COUNT(o.id) as transaction_num",
    		"SUM(o.transaction_total) as transaction_total"])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            // ->leftJoin('GistPOSERPBundle:POSTransactionItem', 'i', ),
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to']);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    protected function processAggregate($data)
    {
        $result = $this->getSalesData($data);
        $return_data = $result;
        $interval = DateAggregate::TYPE_DAILY;

        $chart = $this->getChartVariables();


        $dateFrom = $data['date_from'];
        $dateTo = $data['date_to'];
        $oChart = new Chart();
        $oChart->setYAxis($chart['y_axis']);
        $ag_sum = new DateAggregate($oChart);
        $ag_sum->processSeries(
            new ChartSeries($chart['series_title']),
            $interval,
            $result,
            $dateFrom,
            $dateTo,
            $chart['date_field'],
            $chart['amount_field']
        );

        return $ag_sum->getChart();
    }

    protected function getChartVariables()
    {
        return ['series_title' => 'Daily Sales',
                'date_field' => 'date_create',
                'amount_field' => 'transaction_total',
                'report_title' => 'Daily Sales ',
                'y_axis' => 'Total Sales (PHP)'
            ];
    }
}



