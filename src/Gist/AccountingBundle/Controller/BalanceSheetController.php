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
use Gist\AccountingBundle\Entity\EndingBalance;
use Gist\AccountingBundle\Entity\TrialBalanceSettings;
use Gist\AccountingBundle\Controller\TrialBalanceController;

use DateTime;
use SplFileObject;
use LimitIterator;

class BalanceSheetController extends TrialBalanceController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_bs';
        $this->title = 'Balance Sheet';
        $this->list_title = 'Balance Sheet';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:BalanceSheet:index.html.twig';

        $params['list_title'] = $this->list_title;
        // $params['grid_cols'] = $gl->getColumns();

        // notice of the last month end closing
        $notice = 'No closed month end yet. Kindly add a new month end closing.';
        $params['notice'] = $notice;

        $this->padListParams($params);
        return $this->render($twig_file, $params);
    }

    protected function newBaseClass()
    {
        return '';
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

    public function saveAction()
    {
        try
        {
            $em = $this->getDoctrine()->getManager();
            $am = $this->get('gist_accounting');
            $data = $this->getRequest()->request->all();
            
            $month_end_last = $em->getRepository('GistAccountingBundle:MonthEndClosing')->findAll();

            if (count($month_end_last) == 0) {
                $last_end = $em->getRepository('GistAccountingBundle:MonthEndClosing')
                                   ->createQueryBuilder('o')
                                   ->orderBy('o.id','DESC')
                                   ->getQuery()->getOneOrNullResult();
            }else{
                $last_end = $em->getRepository('GistAccountingBundle:MonthEndClosing')
                               ->createQueryBuilder('o')
                               ->orderBy('o.id','DESC')
                               ->setMaxResults(1)
                               ->getQuery()->getSingleResult();
            }

            if ($last_end == null) {
                // if null can add the new month end closing
                $month_end = new MonthEndClosing();
                $month_end->setYear($data['year'])
                          ->setMonth($data['month'])
                          ->setUserCreate($this->getUser())
                          ->setIsClosed(1);

                $em->persist($month_end);
            }else{
                $d2 = new Datetime($last_end->getYear().'-'.$last_end->getMonth().'-01 00:00:00');
                $month_end_text = $d2->format('F Y');

                $d3 = new Datetime($last_end->getYear().'-'.$last_end->getMonth().'-01 00:00:00');
                $month_end_date = $d3->modify('+1 month');
                $month_end_date = $month_end_date->format('my');

                $date_new = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
                $date_new = $date_new->format('my');

                // if not null, check data if it's the next month of the last month end
                if($date_new == $month_end_date){
                    $month_end = new MonthEndClosing();
                    $month_end->setYear($data['year'])
                              ->setMonth($data['month'])
                              ->setUserCreate($this->getUser())
                              ->setIsClosed(1);

                    $em->persist($month_end);
                }else{
                    //prompt error
                    throw new ValidationException('The selected month is not the next month of the last month end closing of ' . $month_end_text . '.');
                }
            }

            $this->saveEndingClosing($month_end, $data);
            $em->flush();
            $this->addFlash('success','Month End Closed successfully.');
            return $this->redirect($this->generateUrl('gist_month_end_index'));
        }
        catch (ValidationException $e) {
            $this->addFlash('error', $e->getMessage());
            return $this->editError($e);
        } catch (DBALException $e) {
            $this->addFlash('error', 'Database error encountered. Possible duplicate.');            
            // error_log($e->getMessage());
            $this->addFlash('error', $e->getMessage());
            return $this->addError($e);
        }
      
    }

    protected function addError($obj)
    {
        $this->padListParams($params);

        return $this->redirect($this->generateUrl('gist_month_end_index'));
    }

    protected function editError($obj)
    {
        $this->padListParams($params);

        return $this->redirect($this->generateUrl('gist_month_end_index'));
    }

    protected function padListParams(&$params, $obj = null)
    {
        $months = [
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];
        $params['months'] = $months;

        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');

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

    public function generateCSVAction()
    {
        $data = $this->getRequest()->request->all();
        $filename = 'balance_sheet.csv';
        // $tb = $this->getTrialBalanceData($data['date_from'], $data['date_to']);
        $bs = $this->getBalanceSheetData($data['date_from'], $data['date_to']);

        // $date = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        // $date = $date->format('F Y');

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["Cosmetigroup Int'l Corp"]);
        fputcsv($file, ["Statement of Financial Position"]);

        $month_year = $this->getMonthYearArray($data['date_from'], $data['date_to']);
        $date_array = [''];
        foreach ($month_year as $key => $m) {
            $date = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $date = $date->format(' F Y');
            $date_array[] = $date;
        }
        fputcsv($file, []);
        fputcsv($file, $date_array);
        foreach ($bs['assets'] as $assets){
            if (isset($assets['name'])) {
                fputcsv($file, [$assets['name']]);
            }
            if (isset($assets['accounts'])) {
                foreach ($assets['accounts'] as $a) {
                    fputcsv($file, $a);
                }
            }
        }

        $assets_total = ['TOTAL ASSETS'];
        foreach ($bs['assets']['total'] as $t){
            $assets_total[] = $t;
        }
        fputcsv($file, $assets_total);
        fputcsv($file, []);

        foreach ($bs['liability'] as $liabs){
            if (isset($liabs['name'])) {
                fputcsv($file, [$liabs['name']]);
            }
            if (isset($liabs['accounts'])) {
                foreach ($liabs['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        foreach ($bs['capital'] as $cap){
            if (isset($cap['name'])) {
                fputcsv($file, [$cap['name']]);
            }
            if (isset($cap['accounts'])) {
                foreach ($cap['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        $liabs_total = ['TOTAL LIABILITY AND EQUITY'];
        foreach ($bs['liability']['total'] as $key =>  $t){
            $total_le = $bs['liability']['total'][$key] + $bs['capital']['total'][$key];
            $liabs_total[] = $total_le;
        }
        fputcsv($file, $liabs_total);

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    protected function getBalanceSheetData($from, $to)
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

        $assets_main = [];
        $liability_main = [];
        $capital_main = [];

        $asset_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_ASSET]);
        foreach ($asset_accounts as $as) {
            $assets_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];

                $last_month = new DateTime(substr($month_year[0], 0,2).'/'.'01/'.substr($month_year[0], 2));
                $last_month->modify('-1 month');
                $last_month_m = $last_month->format('m');
                $last_month_Y = $last_month->format('Y');

                $clist['code'] = $c->getCode();
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
            

     
            // add all accounts by our tb settings
            $charts_of_account = [];
            foreach ($coa_list['list'] as $list) {
                $charts_of_account[$list['code']] = $list;
            }
         
            foreach ($main_accounts as $acc) {
                $ending = 0;
                foreach($month_year as $key => $m) {
                    if(isset($charts_of_account[$acc->getCode()]['ending_'.$m.'']))
                        $ending = $charts_of_account[$acc->getCode()]['ending_'.$m.''];

                    $assets_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                    $assets_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $ending;

                    if (isset($asset_total[$m])) {
                        $asset_total[$m] += $ending; 
                    }else{
                        $asset_total[$m] = $ending; 
                    }
                }
            }
        }  
        $assets_main['total'] = $asset_total;

        $liab_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_LIABILITY]);

        foreach ($liab_accounts as $as) {
            $liab_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];
                $last_month = new DateTime(substr($month_year[0], 0,2).'/'.'01/'.substr($month_year[0], 2));
                $last_month->modify('-1 month');
                $last_month_m = $last_month->format('m');
                $last_month_Y = $last_month->format('Y');

                $clist['code'] = $c->getCode();
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

            // add all accounts by our tb settings
            $charts_of_account = [];
            foreach ($coa_list['list'] as $list) {
                $charts_of_account[$list['code']] = $list;
            }

            foreach ($main_accounts as $acc) {
                $ending = 0;
                foreach($month_year as $key => $m) {
                    if(isset($charts_of_account[$acc->getCode()]['ending_'.$m.'']))
                        $ending = $charts_of_account[$acc->getCode()]['ending_'.$m.''];

                    $liab_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                    $liab_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $ending;
                    if (isset($liab_total[$m])) {
                        $liab_total[$m] += $ending; 
                    }else{
                        $liab_total[$m] = $ending; 
                    }
                }
            }
        }  
        $liab_main['total'] = $liab_total;

        $capital_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_CAPITAL]);

        foreach ($capital_accounts as $as) {
            $capital_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];
                $last_month = new DateTime(substr($month_year[0], 0,2).'/'.'01/'.substr($month_year[0], 2));
                $last_month->modify('-1 month');
                $last_month_m = $last_month->format('m');
                $last_month_Y = $last_month->format('Y');

                $clist['code'] = $c->getCode();
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

            // add all accounts by our tb settings
            $charts_of_account = [];
            foreach ($coa_list['list'] as $list) {
                $charts_of_account[$list['code']] = $list;
            }

            foreach ($main_accounts as $acc) {
                $ending = 0;
                foreach($month_year as $key => $m) {
                    if(isset($charts_of_account[$acc->getCode()]['ending_'.$m.'']))
                        $ending = $charts_of_account[$acc->getCode()]['ending_'.$m.''];

                    $capital_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                    $capital_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $ending;
                    
                    if (isset($capital_total[$m])) {
                        $capital_total[$m] += $ending; 
                    }else{
                        $capital_total[$m] = $ending; 
                    }
                }
            }
        }  
        $capital_main['total'] = $capital_total;

        $list['assets'] = $assets_main;
        $list['liability'] = $liab_main;
        $list['capital'] = $capital_main;

        return $list;
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