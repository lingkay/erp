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
use Gist\AccountingBundle\Entity\TrialBalanceSettings;
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
        // $tb = $this->getTrialBalanceData($data['date_from'], $data['date_to']);
        $tb = $this->getTrialBalanceDataTotal($data['date_from'], $data['date_to']);

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, $this->trialBalanceHeader($data['date_from'], $data['date_to']));
        fputcsv($file, $this->trialBalanceHeader2($data['date_from'], $data['date_to']));

        foreach ($tb['list'] as $t)
            fputcsv($file, $t);
        foreach ($tb['total'] as $total)
            fputcsv($file, $total);
        foreach ($tb['total_dc'] as $dc)
            fputcsv($file, $dc);

        fputcsv($file, []);
        fputcsv($file, []);

        foreach ($tb['assets'] as $assets)
            fputcsv($file, $assets);
        foreach ($tb['liability'] as $liability)
            fputcsv($file, $liability);
        foreach ($tb['capital'] as $capital)
            fputcsv($file, $capital);
        foreach ($tb['space'] as $space)
            fputcsv($file, $space);
        foreach ($tb['netsales'] as $netsales)
            fputcsv($file, $netsales);
        foreach ($tb['cos'] as $cos)
            fputcsv($file, $cos);
        foreach ($tb['opex'] as $opex)
            fputcsv($file, $opex);
        foreach ($tb['profit'] as $profit)
            fputcsv($file, $profit);
      
        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();


        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    public function generateTableAction($from, $to)
    {
        $data = $this->getRequest()->request->all();
        $tb = $this->getTrialBalanceDataTotal($from, $to);
        $header1 = $this->trialBalanceHeader($from, $to);
        $header2 = $this->trialBalanceHeader2($from, $to);

        $array['tb'] = $tb; 
       
        $array['header1'] = $header1; 
        $array['header2'] = $header2;

        return new JsonResponse($array);
    }

    public function trialBalanceHeader($from, $to)
    {
        $month_year = $this->getMonthYearArray($from, $to);
        // csv headers
        $headers = [
            '',
            '',
            '',
            '',
            '',
        ];

        $last = count($month_year) - 1;
        $headers[] = 'Ending';
        foreach ($month_year as $key => $m) {
            $headers[] = '';
            $headers[] = '';
            $headers[] = 'Ending';
            if ($key != $last) {
                $headers[] = 'Beginning';
            }
        }

        return $headers;
    }

    public function trialBalanceHeader2($from, $to)
    {
        $month_year = $this->getMonthYearArray($from, $to);
        // csv headers
        $headers = [
            '',
            'Chart of Accounts',
            '',
            '',
            '',
        ];

        $last = count($month_year) - 1;

        $last_month = new DateTime($from);
        $last_month->setTime(0,0);
        $last_month->modify('-1 month');
        $last_month = $last_month->format('F Y');

        $headers[] = $last_month;
        foreach ($month_year as $key => $m) {
            $this_month = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $this_month = $this_month->format('F Y');
            $next_month = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $next_month->modify('+1 month');
            $next_month = $next_month->format('F Y');
            $headers[] = 'Debit';
            $headers[] = 'Credit';
            $headers[] = $this_month;
         
            if ($key != $last) {
                $headers[] = $next_month;
            }
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

            $last_month = new DateTime(substr($month_year[0], 0,2).'/'.'01/'.substr($month_year[0], 2));
            $last_month->modify('-1 month');
            $last_month_m = $last_month->format('m');
            $last_month_Y = $last_month->format('Y');

            $last_ending = $em->getRepository('GistAccountingBundle:EndingBalance')
                              ->createQueryBuilder('o')
                              ->where('o.chart_of_account = :COA')
                              ->andWhere('o.month = :MONTH and o.year = :YEAR')
                              ->setParameter(':COA', $c->getID())
                              ->setParameter(':MONTH', $last_month_m)
                              ->setParameter(':YEAR', $last_month_Y)
                              ->getQuery()->getOneOrNullResult();

            if($last_ending == null ) {
                $e = 0;
            }else{
                $e = $last_ending->getEnding();
            }

            $last = count($month_year) - 1;
            $coa_push_list['ending_beginning'] = $e;
            foreach($month_year as $key => $m) {
                if(isset($coa_array[$c->getID()][$m])) {
                    $coa_push_list['debit_'.$m.''] = $coa_array[$c->getID()][$m]['total_debit'];
                    $coa_push_list['credit_'.$m.''] = $coa_array[$c->getID()][$m]['total_credit'];

                    $ending = $coa_array[$c->getID()][$m]['total_debit'] - $coa_array[$c->getID()][$m]['total_credit'];
                    $coa_push_list['ending_'.$m.''] = $e + $ending;
                    if ($key != $last) {
                        $coa_push_list['beginning_'.$m.''] = $e + $ending;
                    }

                    $e = $e + $ending;
                }else{
                    $coa_push_list['debit_'.$m.''] = 0;
                    $coa_push_list['credit_'.$m.''] = 0;
                    $coa_push_list['ending_'.$m.''] = $e;

                    if ($key != $last) {
                        $coa_push_list['beginning_'.$m.''] = $e;
                    }
                    $e = $e;
                }
            }
            $coa_list[$c->getID()] = $coa_push_list;
        }        
     
        return $coa_list;

    }

    public function getTrialBalanceDataTotal($from, $to)
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
            ->join('GistAccountingBundle:ChartOfAccount', 'c', 'WITH', 'o.chart_of_account = c.id');
            

        foreach ($month_year as $key => $m) {
            $m1 = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $y1 = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $m2 = $m1->format('m');
            $y2 = $y1->format('Y');

            if($key == 0) {
                $qb->where('o.month = :MONTH'.$key.' and o.year = :YEAR'.$key.' ')
                   ->setParameter(':MONTH'.$key, $m2)
                   ->setParameter(':YEAR'.$key, $y2);
            }else{
                $qb->orWhere('o.month = :MONTH'.$key.' and o.year = :YEAR'.$key.' ')
                   ->setParameter(':MONTH'.$key, $m2)
                   ->setParameter(':YEAR'.$key, $y2);
            }
        }

        $coa = $qb->getQuery()->getResult();

        $coa_array = [];
        foreach ($coa as $c) {
            $year_format = new DateTime($c->getYear().'-'.$c->getMonth().'-01');
            $year_format = $year_format->format('my');

            if(isset($coa_array[$c->getAccount()->getID()][$year_format])) {
                $coa_arr = $coa_array[$c->getAccount()->getID()][$year_format];
                $coa_array[$c->getAccount()->getID()][$year_format] = [
                    'coa_id' => $c->getAccount()->getID(),
                    'coa_date' => $c->getDateCreate()->format('mdy'),
                    'total_debit' => $coa_arr['total_debit'] += $c->getDebit(),
                    'total_credit' => $coa_arr['total_credit'] += $c->getCredit(),
                ]; 
            }else{
                $coa_array[$c->getAccount()->getID()][$year_format] = [
                    'coa_id' => $c->getAccount()->getID(),
                    'coa_date' => $c->getDateCreate()->format('mdy'),
                    'total_debit' => $c->getDebit(),
                    'total_credit' => $c->getCredit(),
                ];
            }
        }

        $coa_all = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findAll();
        
        $coa_list = [];
        $coa_push_list_total = [];
        foreach ($coa_all as $c) {
            $clist = [];
            $coa_push_list_total['code'] = '';
            $coa_push_list_total['name'] = '';
            $coa_push_list_total['code2'] = '';
            $coa_push_list_total['sp'] = '';
            $coa_push_list_total['sp1'] = '';

            $clist['code'] = $c->getCode();
            $clist['name'] = $c->getName();
            $clist['code2'] = $c->getCode();

            $clist['sp'] = '';
            $clist['sp2'] = '';

            $last_month = new DateTime(substr($month_year[0], 0,2).'/'.'01/'.substr($month_year[0], 2));
            $last_month->modify('-1 month');
            $last_month_m = $last_month->format('m');
            $last_month_Y = $last_month->format('Y');

            $last_ending = $em->getRepository('GistAccountingBundle:EndingBalance')
                              ->createQueryBuilder('o')
                              ->where('o.chart_of_account = :COA')
                              ->andWhere('o.month = :MONTH and o.year = :YEAR')
                              ->setParameter(':COA', $c->getID())
                              ->setParameter(':MONTH', $last_month_m)
                              ->setParameter(':YEAR', $last_month_Y)
                              ->getQuery()->getOneOrNullResult();

            if($last_ending == null ) {
                $e = 0;
            }else{
                $e = $last_ending->getEnding();
            }

            $last = count($month_year) - 1;
            $clist['ending_beginning'] = $e;
            if (isset($coa_push_list_total['ending_beginning'])) {
                $coa_push_list_total['ending_beginning'] += $e;
            }else{
                $coa_push_list_total['ending_beginning'] = $e;
            }
            foreach($month_year as $key => $m) {
                if(isset($coa_array[$c->getID()][$m])) {
                    $clist['debit_'.$m.''] = $coa_array[$c->getID()][$m]['total_debit'];
                    $clist['credit_'.$m.''] = $coa_array[$c->getID()][$m]['total_credit'];

                    if (isset($coa_push_list_total['debit_'.$m.''])) {
                        $coa_push_list_total['debit_'.$m.''] += $coa_array[$c->getID()][$m]['total_debit'];
                    }else{
                        $coa_push_list_total['debit_'.$m.''] = $coa_array[$c->getID()][$m]['total_debit'];
                    }

                    if (isset($coa_push_list_total['credit_'.$m.''])) {
                        $coa_push_list_total['credit_'.$m.''] += $coa_array[$c->getID()][$m]['total_credit'];
                    }else{
                        $coa_push_list_total['credit_'.$m.''] = $coa_array[$c->getID()][$m]['total_credit'];
                    }

                    $ending = $coa_array[$c->getID()][$m]['total_debit'] - $coa_array[$c->getID()][$m]['total_credit'];
                    $clist['ending_'.$m.''] = $e + $ending;

                    if (isset($coa_push_list_total['ending_'.$m.''])) {
                        $coa_push_list_total['ending_'.$m.''] += ($e + $ending);
                    }else{
                        $coa_push_list_total['ending_'.$m.''] = ($e + $ending);
                    }
                    if ($key != $last) {
                        $clist['beginning_'.$m.''] = $e + $ending;
                        if (isset($coa_push_list_total['beginning_'.$m.''])) {
                            $coa_push_list_total['beginning_'.$m.''] += ($e + $ending);
                        }else{
                            $coa_push_list_total['beginning_'.$m.''] = ($e + $ending);
                        }
                    }

                    $e = $e + $ending;
                }else{
                    $clist['debit_'.$m.''] = 0;
                    $clist['credit_'.$m.''] = 0;
                    $clist['ending_'.$m.''] = $e;

                    if (isset($coa_push_list_total['debit_'.$m.''])) {
                        $coa_push_list_total['debit_'.$m.''] += 0;
                    }else{
                        $coa_push_list_total['debit_'.$m.''] = 0;
                    }

                    if (isset($coa_push_list_total['credit_'.$m.''])) {
                        $coa_push_list_total['credit_'.$m.''] += 0;
                    }else{
                        $coa_push_list_total['credit_'.$m.''] = 0;
                    }

                    if (isset($coa_push_list_total['ending_'.$m.''])) {
                        $coa_push_list_total['ending_'.$m.''] += $e;
                    }else{
                        $coa_push_list_total['ending_'.$m.''] = $e;
                    }

                    if ($key != $last) {
                        $clist['beginning_'.$m.''] = $e;
                        if (isset($coa_push_list_total['beginning_'.$m.''])) {
                            $coa_push_list_total['beginning_'.$m.''] += $e;
                        }else{
                            $coa_push_list_total['beginning_'.$m.''] = $e;
                        }
                    }
                }
            }
            $coa_list['list'][] = $clist;
        }        
        $coa_list['total'][] = $coa_push_list_total;

        // add total_debit and total_credit
        $total_dc  = ['','','','',''];  

        // add all accounts by our tb settings
        $charts_of_account = [];
        foreach ($coa_list['list'] as $list) {
            $charts_of_account[$list['code']] = $list;
        }
        $assets    = ['','','','',''];        
        $liability = ['','','','',''];        
        $capital   = ['','','','',''];        
        $netsales  = ['','','','',''];        
        $cos       = ['','','','',''];        
        $opex      = ['','','','',''];        
        $profit    = ['','','','',''];        
        $space     = ['','','','',''];        
        foreach($month_year as $key => $m) {
            // total debit and credit 
            $total_dc[] = '';
            if($coa_list['total'][0]['debit_'.$m.''] == $coa_list['total'][0]['credit_'.$m.'']){
                $total_dc[] = 0;
                $total_dc[] = '';
                $total_dc[] = '';
            }else if ($coa_list['total'][0]['debit_'.$m.''] > $coa_list['total'][0]['credit_'.$m.'']) {
                $total_dc[] = $coa_list['total'][0]['debit_'.$m.''] - $coa_list['total'][0]['credit_'.$m.''];
                $total_dc[] = '';
                $total_dc[] = '';
            }else if ($coa_list['total'][0]['debit_'.$m.''] < $coa_list['total'][0]['credit_'.$m.'']) {
                $total_dc[] = '';
                $total_dc[] = $coa_list['total'][0]['debit_'.$m.''] - $coa_list['total'][0]['credit_'.$m.''];
                $total_dc[] = '';
            }
            // assets
            $assets[] = 'asset';
            // loop the tb settings
            $asset_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_ASSET]);
            $asset_total = 0;
            foreach ($asset_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $asset_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $assets[] = $asset_total;
            $assets[] = '';
            $assets[] = $asset_total;

            // liab
            $liability[] = 'liab';
            // loop the tb settings
            $liab_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_LIABILITY]);
            $liab_total = 0;
            foreach ($liab_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $liab_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $liability[] = $liab_total;
            $liability[] = '';
            $liability[] = $liab_total;

            // netsales
            $netsales[] = 'net sales';
            // loop the tb settings
            $net_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_NET_STALES]);
            $net_total = 0;
            foreach ($net_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $net_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $netsales[] = $net_total;
            $netsales[] = '';
            $netsales[] = $net_total;

            // cos
            $cos[] = 'COS';
            // loop the tb settings
            $cos_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_COS]);
            $cos_total = 0;
            foreach ($cos_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $cos_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $cos[] = $cos_total;
            $cos[] = '';
            $cos[] = $cos_total;

            // opex
            $opex[] = 'OPEX';
            // loop the tb settings
            $opex_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_OPEX]);
            $opex_total = 0;
            foreach ($opex_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $opex_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $opex[] = $opex_total;
            $opex[] = '';
            $opex[] = $opex_total;

            // profit
            $profit_total = $net_total + $cos_total + $opex_total;
            $profit[] = 'net profit';
            $profit[] = $profit_total;
            $profit[] = '';
            $profit[] = '';


            // last cap
            $capital[] = 'cap';
            // loop the tb settings
            $cap_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_CAPITAL]);
            $cap_total = 0;
            foreach ($cap_accounts as $as) {
                $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);
                
                foreach ($main_accounts as $acc) {
                    $cap_total += $charts_of_account[$acc->getCode()]['ending_'.$m.'']; 
                }
            }  
            $cap_ending = $cap_total + $profit_total;
            $capital[] = $cap_total;
            $capital[] = $profit_total;
            $capital[] = $cap_ending;

            // space
            $space_total = $asset_total + $liab_total + $cap_ending;
            $space[] = '';
            $space[] = '';
            $space[] = '';
            $space[] = $space_total;
        }

        $coa_list['assets'][] = $assets;
        $coa_list['liability'][] = $liability;
        $coa_list['capital'][] = $capital;
        $coa_list['netsales'][] = $netsales;
        $coa_list['cos'][] = $cos;
        $coa_list['opex'][] = $opex;
        $coa_list['profit'][] = $profit;
        $coa_list['space'][] = $space;
        $coa_list['total_dc'][] = $total_dc;
   
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
        // $before_start = $from->modify('-1 month');
        // $before_start = $from->format('my');
        // $array[$before_start] = $before_start;

        //loop within the daterange
        if ($start != $end) {
            do {
                $array[] = $from->format('my');     
                $from->modify('+1 month');
                $start = $from->format('my');
            } while ($start != $end);
        }
        
        //push the end
        $array[] = $end;
 
        return $array;
    }
}