<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\AccountingBundle\Entity\TrialBalance;
use DateTime;
use SplFileObject;
use LimitIterator;

class TrialBalanceController extends BaseController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_tb';
        $this->title = 'Trial Balance';
        $this->list_title = 'Trial Balance';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:TrialBalance";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:TrialBalance:index.html.twig';

        $params['list_title'] = $this->list_title;
        // $params['grid_cols'] = $gl->getColumns();
        $this->padListParams($params);
        return $this->render($twig_file, $params);
    }

    protected function newBaseClass()
    {
        return new TrialBalance();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'chart_of_account', 'getAccount'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Account Name', 'getNameCode', 'name', 'a'),
            $grid->newColumn('Record Date', 'getRecordDate', 'record_date', 'o', [$this,'formatDate']),
            $grid->newColumn('Particulars', 'getNotes', 'notes'),
     
            $grid->newColumn('Debit', 'getDebit', 'debit', 'o', [$this,'formatPrice']),
            $grid->newColumn('Credit', 'getCredit', 'credit',  'o', [$this,'formatPrice']),
        );
    }

   
    
    protected function update($data)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');

        $record_date = new DateTime($data['record_date']);

        foreach ($data['account'] as $key => $account_id) {
            $cdj_entry = new CDJJournalEntry();
            $account = $am->findChartOfAccount($account_id);
            $cdj_entry->setAccount($account)
                ->setDebit($data['debit'][$key])
                ->setCredit($data['credit'][$key])
                ->setNotes($data['notes'][$key])
                ->setUserCreate($this->getUser())
                ->setRecordDate($record_date);

            $em->persist($cdj_entry);
            $em->flush();

            $am->addTrialBalance($cdj_entry);
        }
    }

     protected function padListParams(&$params, $obj = null)
    {
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        $params['from'] = $this->date_from->format('m-d-Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['to'] = $this->date_to->format('m-d-Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');

        return $params;

    }

    protected function hookPreAction()
    {
        // $this->getControllerBase();
        if($this->getRequest()->get('date_from') != null){
            $this->date_from = new DateTime($this->getRequest()->get('date_from'));
        }else {
           $date_from = new DateTime();
           $date_from->modify('first day of this month');
           $this->date_from = $date_from;
        }

        if($this->getRequest()->get('date_to') != null){
            $this->date_to = new DateTime($this->getRequest()->get('date_to'));
        }else {
           $date_to = new DateTime();
           $date_to->modify('last day of this month');
           $this->date_to = $date_to;
        }
    }

    protected function filterGrid()
    {
        $this->date_from->setTime(0,0);
        $this->date_to->setTime(23,59);

        $fg = parent::filterGrid();
        $fg->where('o.record_date between :date_from and :date_to ')
            ->setParameter("date_from", $this->date_from)
            ->setParameter("date_to", $this->date_to);
     
        return $fg;
    }

    public function generateCSVAction()
    {
        $data = $this->getRequest()->request->all();
        $filename = 'trial_balance.csv';
        $tb = $this->getTrialBalanceData($data['date_from'], $data['date_to']);

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, $this->trialBalanceHeader($data['date_from'], $data['date_to']));

        foreach ($tb as $t)
            fputcsv($file, $t);
        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();


        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    public function trialBalanceHeader($from, $to)
    {
        $month_year = $this->getMonthYearArray($from, $to);
        // csv headers
        $headers = [
            'Account Name',
            'Account Code',
        ];

        foreach ($month_year as $m) {
            $headers[] = 'Debit';
            $headers[] = 'Credit';
        }

        return $headers;
    }

    public function getTrialBalanceData($from, $to)
    {
        $em = $this->getDoctrine()->getManager();
        $month_year = $this->getMonthYearArray($from, $to);
        $dateFrom = new DateTime($from);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($to);
        $dateTo->setTime(23,59);
        $from = $dateFrom;
        $to = $dateTo;

        //get COA balance per month
        $qb = $em->createQueryBuilder();
        $qb->select('o')
            ->from('GistAccountingBundle:TrialBalance', 'o')
            ->join('GistAccountingBundle:ChartOfAccount', 'c', 'WITH', 'o.chart_of_account = c.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $from)
            ->setParameter('date_to', $to);

        $coa = $qb->getQuery()->getResult();

        $coa_array = [];
        foreach ($coa as $c) {
            if(isset($coa_array[$c->getAccount()->getID()][$c->getDateCreate()->format('my')])) {
                $coa_arr = $coa_array[$c->getAccount()->getID()][$c->getDateCreate()->format('my')];
                $coa_array[$c->getAccount()->getID()][$c->getDateCreate()->format('my')] = [
                    'coa_id' => $c->getAccount()->getID(),
                    'coa_date' => $c->getDateCreate()->format('mdy'),
                    'total_debit' => $coa_arr['total_debit'] += $c->getDebit(),
                    'total_credit' => $coa_arr['total_credit'] += $c->getCredit(),
                ]; 
            }else{
                $coa_array[$c->getAccount()->getID()][$c->getDateCreate()->format('my')] = [
                    'coa_id' => $c->getAccount()->getID(),
                    'coa_date' => $c->getDateCreate()->format('mdy'),
                    'total_debit' => $c->getDebit(),
                    'total_credit' => $c->getCredit(),
                ];
            }
        }

        $coa_all = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findAll();
        
        $coa_list = [];
        foreach ($coa_all as $c) {
            $coa_push_list = [];
            $coa_push_list['name'] = $c->getName();
            $coa_push_list['code'] = $c->getCode();

            foreach($month_year as $key => $m) {
                if(isset($coa_array[$c->getID()][$m])) {
                    $coa_push_list['debit_'.$m.''] = $coa_array[$c->getID()][$m]['total_debit'];
                    $coa_push_list['credit_'.$m.''] = $coa_array[$c->getID()][$m]['total_credit'];
                }else{
                    $coa_push_list['debit_'.$m.''] = 0;
                    $coa_push_list['credit_'.$m.''] = 0;
                }
            }
            $coa_list[$c->getID()] = $coa_push_list;
        }        
        
        return $coa_list;

    }

    protected function getMonthYearArray($from, $to)
    {
        $dateFrom = new DateTime($from);
        $dateFrom->setTime(0,0);
        $dateTo = new DateTime($to);
        $dateTo->setTime(23,59);
        $from = $dateFrom;
        $to = $dateTo;

        $start = $dateFrom->format('my');
        $end = $dateTo->format('my');

        $array = [];
        //1 month before the start month
        $before_start = $from->modify('-1 month');
        $before_start = $before_start->format('my');
        $array[$before_start] = $before_start;

        //loop within the daterange
        do {
            $array[$from->format('my')] = $from->format('my');     
            $from->modify('+1 month');
            $start = $from->format('my');
        } while ($start != $end);
        
        //push the end
        $array[$end] = $end;
 
        return $array;
    }
}