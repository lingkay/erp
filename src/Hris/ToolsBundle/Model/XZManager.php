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
        $dateFrom = new DateTime($data['date_to']);
        // $dateFrom->modify('-7 days');
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date_to']);
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
            ->andWhere('o.pos_location = :branch ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch']);

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

    public function getSalesPerProduct($data)
    {
    	$dateFrom = new DateTime($data['date_from']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date_to']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;
        $result = $this->getSalesPerProductData($data);
        return $result;
    }

    protected function getSalesPerProductData($data)
    {
	  	$qb = $this->em->createQueryBuilder();
    	$qb->select([ "count(o) as quantity",
    				"sum(o.total_amount) as total", 
    				"o.name",
    				"sum(o.adjusted_price) as adjusted_price",
    				"sum(o.discount_value) as discount",
    				"avg(o.total_amount) as average"])
            ->from('GistPOSERPBundle:POSTransactionItem', 'o')
            ->leftJoin('GistPOSERPBundle:POSTransaction', 't', 'WITH', 'o.transaction = t.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('t.pos_location = :branch ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch'])
            ->groupby('o.product_id');
        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getSalesPerLocation($data)
    {
    	$dateFrom = new DateTime($data['date_from']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date_to']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;
        $result = $this->getSalesPerLocationData($data);
        return $result;
    }

    protected function getSalesPerLocationData($data)
    {
	  	$qb = $this->em->createQueryBuilder();
    	$qb->select(["sum(o.transaction_total) as total", "l.name as name", "l.id as id"])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->leftJoin('o.pos_location','l')
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch'])
            ->groupby('o.pos_location')
            ->orderby('total','desc');

        $result = $qb->getQuery()->getResult();
     
        return $result;
    }

    public function getCustomerSales($data)
    {
    	$dateFrom = new DateTime("03/17/2018");
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime("05/17/2018");
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;
        $result = $this->getCustomerSalesData($data);
        return $result;
    }

    protected function getCustomerSalesData($data)
    {
	  	$qb = $this->em->createQueryBuilder();
    	$qb->select(["count(o) as qty", 
    				"concat(c.first_name,' ' ,c.last_name) as name", 
    				"o.transaction_total as total",
    				"c.id as id",
    				"c.date_create as date"])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->leftJoin('o.customer','c')
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->groupby('o.customer')
            ->orderby('total','desc');

        $result = $qb->getQuery()->getResult();
     
        return $result;
    }
}



