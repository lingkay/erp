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

    
    // public function getSalesChart($data)
    // {
    //     $dateFrom = new DateTime($data['date_from']);
    //     // $dateFrom->modify('-7 days');
    //     $dateFrom->setTime(0,0);
    //     $dateTo = new DateTime($data['date_to']);
    //     $dateTo->setTime(23,59);
    //     $data['date_from'] = $dateFrom;
    //     $data['date_to'] = $dateTo;
    //     $chart = $this->processAggregate($data);
    //     return $chart;
    // }

    public function getSalesChart($data)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->modify('-7 day');
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
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
            "SUM(o.transaction_total) as transaction_total",
            "SUM(o.transaction_total) as sub_total",
    		"SUM(o.total_discount) as total_discount",])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('o.pos_location = :branch ')
            ->andWhere('o.transaction_mode = :normal or o.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch']);

        $result = $qb->getQuery()->getResult();

        return $result;
    }

    public function getSalesTableData($data)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->modify('-7 day');
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        $qb = $this->em->createQueryBuilder();
        $qb->select(["COUNT(o.id) as total_sales",
                    "SUM(o.transaction_total) as sub_total",
                    "SUM(o.total_discount) as total_discount",])
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('o.pos_location = :branch ')
            ->andWhere('o.transaction_mode = :normal or o.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
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
    	$dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
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
            ->andWhere('t.transaction_mode = :normal or t.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
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
    	$dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
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
            ->andWhere('o.transaction_mode = :normal or o.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
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
            ->andWhere('o.transaction_mode = :normal or o.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->groupby('o.customer')
            ->orderby('total','desc');

        $result = $qb->getQuery()->getResult();
     
        return $result;
    }

    protected function getTransaction($data)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('o.pos_location = :branch ')
            ->andWhere('o.transaction_mode = :normal or o.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch']);

        $result = $qb->getQuery()->getResult();
     
        return $result;
    }

    protected function getAllTransaction($data)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
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

    protected function getEmployeeAttendance($data)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        $qb = $this->em->createQueryBuilder();
        $qb->select(["u.id as user_id",
                     "min(o.date) as time_in",
                     "max(o.date) as time_out"])    
            ->from('HrisWorkforceBundle:Attendance', 'o')
            ->join('GistUserBundle:User', 'u', 'WITH', 'o.employee = u.id')
            ->where('o.date between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->groupby('u.id');

        $result = $qb->getQuery()->getResult();

        $array = [];
        foreach ($result as $res) {
            $date = new DateTime($res['time_in']);
            $date = $date->format('mdy');
            $array[$res['user_id']][$date] = $res;
        }
     
        return $array;
    }

    public function getEmployeeChart($data)
    {
        $result = $this->getTransaction($data);
        $attendance = $this->getEmployeeAttendance($data);
        
        $array = [];
        foreach ($result as $key => $res) {
            $in = '';
            $out = '';
            $total = '';
            if(isset($attendance[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')])) {
                $dteStart = new DateTime($attendance[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')]['time_in']); 
                $dteEnd   = new DateTime($attendance[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')]['time_out']); 
                $dteDiff  = $dteEnd->diff($dteStart);
                $in = $dteStart->format('H:i');
                $out = $dteEnd->format('H:i');
                $hours = $dteDiff->format("%h");
                $minutes = $dteDiff->format("%I");
                $total = $hours.'.'.($minutes/60);
            }
            if(isset($array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')])) {
                $arr = $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')];
                $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')] = [
                    'employee' => $res->getUserCreate()->getName(),
                    'employee_id' => $res->getUserCreate()->getUsername(),
                    'employee_pic' => $res->getUserCreate()->getProfilePicture() != null ? $res->getUserCreate()->getProfilePicture()->getURL() : '',
                    'in' => $in,
                    'out' => $out,
                    'total' => $total,
                    'sales' => ($arr['sales'] + 1),
                    'amount' => $arr['amount'] + $res->getTransactionTotal(),
                ];
            }else{
                $array[$res->getUserCreate()->getID()][$res->getDateCreate()->format('mdy')] = [
                    'employee' => $res->getUserCreate()->getName(),
                    'employee_id' => $res->getUserCreate()->getUsername(),
                    'employee_pic' => $res->getUserCreate()->getProfilePicture() != null ? $res->getUserCreate()->getProfilePicture()->getURL() : '',
                    'in' => $in,
                    'out' => $out,
                    'total' => $total,
                    'sales' => 1,
                    'amount' => $res->getTransactionTotal() != null ?  $res->getTransactionTotal() : 0,
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
                $is_new = 'TRUE';
                $cdate_created = $res->getCustomer()->getDateCreateFormatted();
                $tdate_created = $res->getDateCreateFormatted();
                if($cdate_created == $tdate_created)
                    $is_new = 'FALSE';

                if(isset($array[$res->getCustomer()->getID()])) {
                    $arr = $array[$res->getCustomer()->getID()];
                    $array[$res->getCustomer()->getID()] = [
                        'name' => $res->getCustomer()->getNameFormatted(),
                        'id' => $res->getCustomer()->getID(),
                        'sales' => ($arr['sales'] + 1),
                        'amount' => $arr['amount'] + $res->getTransactionTotal(),
                        'arrival' => '',
                        'is_new' => $is_new,
                    ];
                }else{
                    $array[$res->getCustomer()->getID()] = [
                        'name' => $res->getCustomer()->getNameFormatted(),
                        'id' => $res->getCustomer()->getID(),
                        'sales' => 1,
                        'amount' => $res->getTransactionTotal() != null ?  $res->getTransactionTotal() : 0,
                        'arrival' => '',
                        'is_new' => $is_new,
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

        //Credit Card Interest
        $cc_interests = 0;
        foreach ($result as $key => $res) {
            if ($res->hasPayments()) {
                foreach ($res->getPayments() as $k => $pay) {
                    $interest = 0;
                    if($pay->getInterest() != '') {
                        $interest = $pay->getInterest();  
                    }
                    switch ($pay->getType()) {
                        case 'Cash':
                            $cash += $pay->getAmount();
                            $cash_count++;
                            break;
                        case 'Credit Card':
                            $credit_card += $pay->getAmount();
                            $credit_card_count++;
                            $cc_interests += $interest;
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
            'cc_interests' => $cc_interests,
        ]; 

        return $array;
    }

    public function getTransactionsData($data)
    {
        $result = $this->getAllTransaction($data);

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
                    $normal += $res->getTransactionTotal();
                    $normal_count++;               
                    break;
                case 'upsale':
                    $upsale += $res->getTransactionTotal();
                    $upsale_count++;
                    break;
                case 'deposit':
                    $deposit += $res->getTransactionTotal();
                    $deposit_count++;
                    $deposit_outof += $res->getDepositAmount();
                    $deposit_balance += $res->getBalance();
                    break;
                case 'quotation':
                    $quotation += $res->getTransactionTotal();
                    $quotation_count++;
                    break;
                case 'frozen':
                    $frozen += $res->getTransactionTotal();
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

    public function getCommissionData($data, $conf)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistPOSERPBundle:POSTransactionSplit', 'o')
            ->join('GistPOSERPBundle:POSTransaction', 't', 'WITH', 'o.transaction = t.id')
            ->join('GistUserBundle:User', 'u', 'WITH', 'o.consultant = u.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('t.pos_location = :branch ')
            ->andWhere('t.transaction_mode = :normal or t.transaction_mode = :upsell')
            ->setParameter('normal', 'normal')
            ->setParameter('upsell', 'upsell')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('branch', $data['branch']);

        $result = $qb->getQuery()->getResult();
       
        $qb = $this->em->createQueryBuilder();
        $qb->select(['sum(o.debit) as total_debit',
                     'sum(o.credit) as total_credit',
                     'u.id as user_id'])
            ->from('HrisToolsBundle:EmployeeBonusFine', 'o')
            ->join('GistUserBundle:User', 'u', 'WITH', 'o.employee = u.id')
            ->where('o.date_released between :date_from and :date_to ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->groupby('u.id');

        $bonus_and_fines = $qb->getQuery()->getResult();

        $bnf = [];
        foreach ($bonus_and_fines as $b) {
            $bnf[$b['user_id']] = $b;
        }

        $array = [];
        foreach ($result as $key => $res) {
            $percentage = 0;
            $commission = 0;
            $bonus = 0;
            $fine  = 0;
            if($res->getCommission() != null ){
                $commission = $res->getCommission();
            }

            if (isset($bnf[$res->getConsultant()->getID()])) {
                $bonus = $bnf[$res->getConsultant()->getID()]['total_debit'];
                $fine  = $bnf[$res->getConsultant()->getID()]['total_credit'];
            }

            if(isset($array[$res->getConsultant()->getID()])) {
                $arr = $array[$res->getConsultant()->getID()];
                $total = $arr['total'] += $commission;
                $sales_total = $arr['sales'] += $res->getTransaction()->getTransactionTotal();
                $com_total = $arr['commission'] += $commission;
                $percentage =  (($com_total/$sales_total)*100);

                $array[$res->getConsultant()->getID()] = [
                    'employee' => $res->getConsultant()->getName(),
                    'employee_id' => $res->getConsultant()->getUsername(),
                    'employee_pic' => $res->getConsultant()->getProfilePicture() != null ? $res->getConsultant()->getProfilePicture()->getURL() : '',
                    'percent' => $percentage,
                    'commission' => $com_total,
                    'fine' => $fine,
                    'bonus' => $bonus,
                    'sales' => $sales_total,
                    'total' => $total + ($bonus - $fine),
                ];
            }else{
                $total = $commission;
                $percentage =  (($commission/$res->getTransaction()->getTransactionTotal())*100);

                $array[$res->getConsultant()->getID()] = [
                    'employee' => $res->getConsultant()->getName(),
                    'employee_id' => $res->getConsultant()->getUsername(),
                    'employee_pic' => $res->getConsultant()->getProfilePicture() != null ? $res->getConsultant()->getProfilePicture()->getURL() : '',
                    'percent' => $percentage,
                    'commission' => $commission,
                    'fine' => $fine,
                    'bonus' => $bonus,
                    'sales' => $res->getTransaction()->getTransactionTotal(),
                    'total' => $total + ($bonus - $fine),
                ];
            }
        }
        
        return $array;
    }
    
    public function getInventoryData($data, $conf)
    {
        $dateFrom = new DateTime($data['date']);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($data['date']);
        $dateTo->setTime(23,59);
        $data['date_from'] = $dateFrom;
        $data['date_to'] = $dateTo;

        // Items Opening and Closing
        $inv_acct = $this->em->getRepository('GistLocationBundle:POSLocations')->find($data['branch'])->getInventoryAccount();
        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistInventoryBundle:Counting', 'o')
            ->join('GistInventoryBundle:Account', 'a', 'WITH', 'o.inventory_account = a.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('a.id = :inv_acct ')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('inv_acct', $inv_acct->getID());

        $counting = $qb->getQuery()->getResult();

        $array = [];
        $array['Opening']['count'] = 0;
        $array['Opening']['responsible'] = '';
        $array['Closing']['count'] = 0;
        $array['Closing']['responsible'] = '';
        foreach ($counting as $key => $c) {
            $array[$c->getCountTimeSlot()]['count'] = $c->getEntriesTotalQty(); 
            $array[$c->getCountTimeSlot()]['responsible'] = $c->getUserCreate()->getName(); 
        }

        // Items In and Out
        $items_in = 0; 
        $items_in_name = ''; 
        $items_out = 0; 
        $items_out_name = ''; 

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistInventoryBundle:StockTransfer', 'o')
            ->join('GistInventoryBundle:Account', 'a', 'WITH', 'o.destination_inv_account = a.id')
            ->where('o.date_received between :date_from and :date_to ')
            ->andWhere('a.id = :inv_acct ')
            ->andWhere('o.status = :status')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('status', 'arrived')
            ->setParameter('inv_acct', $inv_acct->getID());

        $stock_transfer_in = $qb->getQuery()->getResult();

        foreach ($stock_transfer_in as $in) {
            $items_in += $in->countItemEntries();
            $items_in_name = $in->getReceivingUser()->getName();
        }

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistInventoryBundle:StockTransfer', 'o')
            ->join('GistInventoryBundle:Account', 'a', 'WITH', 'o.source_inv_account = a.id')
            ->where('o.date_received between :date_from and :date_to ')
            ->andWhere('a.id = :inv_acct ')
            ->andWhere('o.status = :status')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('status', 'arrived')
            ->setParameter('inv_acct', $inv_acct->getID());

        $stock_transfer_out = $qb->getQuery()->getResult();

        foreach ($stock_transfer_out as $out) {
            $items_out += $out->countItemEntries();
            $items_out_name = $out->getReceivingUser()->getName();
        }

        $array['Items In']['count'] = $items_in; 
        $array['Items In']['name'] = $items_in_name; 
        $array['Items Out']['count'] = $items_out; 
        $array['Items Out']['name'] = $items_out_name; 

        // Items Sales and Provided
        $sales = $this->getAllTransaction($data);

        $items_sales = 0; 
        foreach ($sales as $s) {
            $items_sales += count($s->getItems());
        }
        $array['Items Sales']['count'] = $items_sales; 

        // Items New Damaged
        $items_new_damaged = 0; 
        $items_new_damaged_user = ''; 

        $qb = $this->em->createQueryBuilder();
        $qb->select('o')
            ->from('GistInventoryBundle:DamagedItemsEntry', 'o')
            ->join('GistInventoryBundle:DamagedItems', 'd', 'WITH', 'o.damaged_items = d.id')
            ->join('GistInventoryBundle:Account', 'a', 'WITH', 'd.destination_inv_account = a.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->andWhere('o.status = :status')
            ->setParameter('date_from', $data['date_from'])
            ->setParameter('date_to', $data['date_to'])
            ->setParameter('status', 'returned');
            

        if ($inv_acct->getDamagedContainer() != null) {
            $qb->andWhere('a.id = :inv_acct ')
               ->setParameter('inv_acct', $inv_acct->getDamagedContainer()->getID());
        }

        $damaged_items = $qb->getQuery()->getResult();

        foreach ($damaged_items as $d) {
            $items_new_damaged += $d->getQuantity();
            $items_new_damaged_user = $d->getRequestingUser()->getName();
        }

        $array['Items New Damaged']['count'] = $items_new_damaged; 
        $array['Items New Damaged']['count_nega'] = $items_new_damaged * -1; 
        $array['Items New Damaged']['name'] = $items_new_damaged_user; 

        return $array;
    }
}



