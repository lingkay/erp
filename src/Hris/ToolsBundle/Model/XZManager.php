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
        $dateFrom = new DateTime($data['date_from']);
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
            ->groupby('o.product_id')
            ->orderBy('total','DESC')
            ->setMaxResults(10);
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

    protected function getTransaction($data)
    {
        $dateFrom = new DateTime($data['date_from']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date_to']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('o.pos_location = :branch ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch']);

        $result = $qb->getQuery()->getResult();
     
        return $result;
    }

    public function getEmployeeChart($data)
    {
        $result = $this->getTransaction($data);

        $array = [];
        foreach ($result as $key => $res) {
            if(isset($array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')])) {
                $arr = $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')];
                $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')] = [
                    'employee' => $res->getUserCreate()->getName(),
                    'employee_id' => $res->getUserCreate()->getID(),
                    'employee_pic' => $res->getUserCreate()->getProfilePicture() != null ? $res->getUserCreate()->getProfilePicture()->getURL() : '',
                    'in' => '',
                    'out' => '',
                    'total' => '',
                    'sales' => ($arr['sales'] + 1),
                    'amount' => $arr['amount'] + $res->getCartOrigTotal(),
                ];
            }else{
                $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')] = [
                    'employee' => $res->getUserCreate()->getName(),
                    'employee_id' => $res->getUserCreate()->getID(),
                    'employee_pic' => $res->getUserCreate()->getProfilePicture() != null ? $res->getUserCreate()->getProfilePicture()->getURL() : '',
                    'in' => '',
                    'out' => '',
                    'total' => '',
                    'sales' => 1,
                    'amount' => $res->getCartOrigTotal() != null ?  $res->getCartOrigTotal() : 0,
                ];
            }
        }
      
        return $array;
    }

    public function getCustomerChart($data)
    {
        $result = $this->getTransaction($data);

        $array = [];
        foreach ($result as $key => $res) {
            if ($res->getCustomer() != null) {
                if(isset($array[$res->getCustomer()->getID()])) {
                    $arr = $array[$res->getCustomer()->getID()];
                    $array[$res->getCustomer()->getID()] = [
                        'name' => $res->getCustomer()->getNameFormatted(),
                        'id' => $res->getCustomer()->getID(),
                        'sales' => ($arr['sales'] + 1),
                        'amount' => $arr['amount'] + $res->getCartOrigTotal(),
                        'arrival' => '',
                        'is_new' => '',
                    ];
                }else{
                    $array[$res->getCustomer()->getID()] = [
                        'name' => $res->getCustomer()->getNameFormatted(),
                        'id' => $res->getCustomer()->getID(),
                        'sales' => 1,
                        'amount' => $res->getCartOrigTotal() != null ?  $res->getCartOrigTotal() : 0,
                        'arrival' => '',
                        'is_new' => '',
                    ];
                }
            }
        }
      
        return $array;
    }

    public function getModeChart($data)
    {
        $result = $this->getTransaction($data);

        $array = [];
        //count
        $cash_count = 0;
        $credit_card_count = 0;
        $check_count = 0;
        $gift_card_count = 0;
        //amount
        $cash = 0;
        $credit_card = 0;
        $check = 0;
        $gift_card = 0;
        foreach ($result as $key => $res) {
            if ($res->hasPayments()) {
                foreach ($res->getPayments() as $k => $pay) {
                    switch ($pay->getType()) {
                        case 'Cash':
                            $cash += $pay->getAmount();
                            $cash_count++;
                            break;
                        case 'Credit Card':
                            $credit_card += $pay->getAmount();
                            $credit_card_count++;
                            break;
                        case 'Check':
                            $check += $pay->getAmount();
                            $check_count++;
                            break;
                        case 'Gift Card':
                            $gift_card += $pay->getAmount();
                            $gift_card_count++;
                            break;
                        default:
                            break;
                    }
                }
            }
        }
        
        $count_total = $cash_count + $credit_card_count + $check_count + $gift_card_count;
        $amount_total = $cash + $credit_card + $check + $gift_card;
        $array = [
            'cash_count' => $cash_count,
            'credit_card_count' => $credit_card_count,
            'check_count' => $check_count,
            'gift_card_count' => $gift_card_count,
            'cash' => $cash,
            'credit_card' => $credit_card,
            'check' => $check,
            'gift_card' => $gift_card,
            'count_total' => $count_total,
            'amount_total' => $amount_total,
        ]; 

        return $array;
    }

    public function getTransactionsData($data)
    {
        $result = $this->getTransaction($data);

        $array = [];
        //amount
        $normal = 0;
        $upsale = 0;
        $deposit = 0;
        $quotation = 0;
        $frozen = 0;
        //count
        $normal_count = 0;
        $upsale_count = 0;
        $deposit_count = 0;
        $quotation_count = 0;
        $frozen_count = 0;

        //deposit other data
        $deposit_outof = 0;
        $deposit_balance = 0;

        foreach ($result as $key => $res) {
            switch ($res->getTransactionMode()) {
                case 'normal':
                    $normal += $res->getCartOrigTotal();
                    $normal_count++;               
                    break;
                case 'upsale':
                    $upsale += $res->getCartOrigTotal();
                    $upsale_count++;
                    break;
                case 'deposit':
                    $deposit += $res->getCartOrigTotal();
                    $deposit_count++;
                    $deposit_outof += $res->getDepositAmount();
                    $deposit_balance += $res->getBalance();
                    break;
                case 'quotation':
                    $quotation += $res->getCartOrigTotal();
                    $quotation_count++;
                    break;
                case 'frozen':
                    $frozen += $res->getCartOrigTotal();
                    $frozen_count++;
                    break;
                default:
                    break;
            }
        }

        $array = [
            'normal' => $normal,
            'upsale' => $upsale,
            'deposit' => $deposit,
            'quotation' => $quotation,
            'frozen' => $frozen,
            'normal_count' => $normal_count,
            'upsale_count' => $upsale_count,
            'deposit_count' => $deposit_count,
            'quotation_count' => $quotation_count,
            'frozen_count' => $frozen_count,
            'deposit_outof' => $deposit_outof,
            'deposit_balance' => $deposit_balance,
        ];

        return $array;
    }

    public function getCommissionData($data)
    {
        $result = $this->getTransaction($data);

        $array = [];
        foreach ($result as $key => $res) {
            if ($res->getTransactionTotal() != 0 && $res->getCartOrigTotal()) {
                $array[$res->getID()] = [
                    'employee' => $res->getUserCreate()->getName(),
                    'employee_id' => $res->getUserCreate()->getID(),
                    'employee_pic' => $res->getUserCreate()->getProfilePicture() != null ? $res->getUserCreate()->getProfilePicture()->getURL() : '',
                    'percent' => $res->getPercentOfSale(),
                    'commission' => $res->getTransactionTotal(),
                    'fine' => '',
                    'bonus' => '',
                    'sales' => $res->getCartOrigTotal(),
                    'total' => $res->getTransactionTotal(),
                ];
            }
        }

        return $array;
    }
    
}



