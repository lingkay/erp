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
        $bs = $this->getBalanceSheetData($data['month'], $data['year']);

        $date = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        $date = $date->format('F Y');

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["Cosmetigroup Int'l Corp"]);
        fputcsv($file, ["Statement of Financial Position"]);
        fputcsv($file, ["as of ".$date.""]);
        fputcsv($file, []);

        foreach ($bs['assets'] as $assets){
            fputcsv($file, [$assets['name']]);
            if (isset($assets['accounts'])) {
                foreach ($assets['accounts'] as $a) {
                    fputcsv($file, $a);
                }
            }
        }
        fputcsv($file, ['TOTAL ASSETS',$bs['assets']['total']]);
        fputcsv($file, []);

        foreach ($bs['liability'] as $liabs){
            fputcsv($file, [$liabs['name']]);
            if (isset($liabs['accounts'])) {
                foreach ($liabs['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        foreach ($bs['capital'] as $cap){
            fputcsv($file, [$cap['name']]);
            if (isset($cap['accounts'])) {
                foreach ($cap['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        $total_le = $bs['liability']['total'] + $bs['capital']['total'];
        fputcsv($file, ['TOTAL LIABILITY AND EQUITY',$total_le]);

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    protected function getBalanceSheetData($month, $year)
    {
        $em = $this->getDoctrine()->getManager();

         //get COA balance per month
        $qb = $em->createQueryBuilder();
        $qb->select('o')
            ->from('GistAccountingBundle:TrialBalance', 'o')
            ->join('GistAccountingBundle:ChartOfAccount', 'c', 'WITH', 'o.chart_of_account = c.id')
            ->where('o.month = :MONTH and o.year = :YEAR ')
            ->setParameter(':MONTH',$month)
            ->setParameter(':YEAR',$year);

        $coa = $qb->getQuery()->getResult();

        $coa_array = [];
        foreach ($coa as $c) {
            if(isset($coa_array[$c->getAccount()->getID()])) {
                $coa_arr = $coa_array[$c->getAccount()->getID()];
                $coa_array[$c->getAccount()->getID()] = [
                    'coa_id' => $c->getAccount()->getID(),
                    'coa_date' => $c->getDateCreate()->format('mdy'),
                    'total_debit' => $coa_arr['total_debit'] += $c->getDebit(),
                    'total_credit' => $coa_arr['total_credit'] += $c->getCredit(),
                ]; 
            }else{
                $coa_array[$c->getAccount()->getID()] = [
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
        $asset_total = 0;
        foreach ($asset_accounts as $as) {
            $assets_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];
                $last_month = new DateTime($month.'/'.'01/'.$year);
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

                $clist['ending_beginning'] = $e;
                if (isset($coa_push_list_total['ending_beginning'])) {
                    $coa_push_list_total['ending_beginning'] += $e;
                }else{
                    $coa_push_list_total['ending_beginning'] = $e;
                }
                if(isset($coa_array[$c->getID()])) {
                    $clist['debit'] = $coa_array[$c->getID()]['total_debit'];
                    $clist['credit'] = $coa_array[$c->getID()]['total_credit'];

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += $coa_array[$c->getID()]['total_debit'];
                    }else{
                        $coa_push_list_total['debit'] = $coa_array[$c->getID()]['total_debit'];
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += $coa_array[$c->getID()]['total_credit'];
                    }else{
                        $coa_push_list_total['credit'] = $coa_array[$c->getID()]['total_credit'];
                    }

                    $ending = $coa_array[$c->getID()]['total_debit'] - $coa_array[$c->getID()]['total_credit'];
                    $clist['ending'] = $e + $ending;

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['ending'] = ($e + $ending);
                    }
                    
                    $clist['beginning'] = $e + $ending;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['beginning'] = ($e + $ending);
                    }

                    $e = $e + $ending;
                }else{
                    $clist['debit'] = 0;
                    $clist['credit'] = 0;
                    $clist['ending'] = $e;

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += 0;
                    }else{
                        $coa_push_list_total['debit'] = 0;
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += 0;
                    }else{
                        $coa_push_list_total['credit'] = 0;
                    }

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += $e;
                    }else{
                        $coa_push_list_total['ending'] = $e;
                    }

                    $clist['beginning'] = $e;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += $e;
                    }else{
                        $coa_push_list_total['beginning'] = $e;
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
                if(isset($charts_of_account[$acc->getCode()]['ending']))
                    $ending = $charts_of_account[$acc->getCode()]['ending'];

                $assets_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                $assets_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $ending;
                $asset_total += $ending; 
            }
        }  
        $assets_main['total'] = $asset_total;
        
        $liab_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_LIABILITY]);
        $liab_total = 0;
        foreach ($liab_accounts as $as) {
            $liab_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];
                $last_month = new DateTime($month.'/'.'01/'.$year);
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

                $clist['ending_beginning'] = $e;
                if (isset($coa_push_list_total['ending_beginning'])) {
                    $coa_push_list_total['ending_beginning'] += $e;
                }else{
                    $coa_push_list_total['ending_beginning'] = $e;
                }
                if(isset($coa_array[$c->getID()])) {
                    $clist['debit'] = $coa_array[$c->getID()]['total_debit'];
                    $clist['credit'] = $coa_array[$c->getID()]['total_credit'];

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += $coa_array[$c->getID()]['total_debit'];
                    }else{
                        $coa_push_list_total['debit'] = $coa_array[$c->getID()]['total_debit'];
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += $coa_array[$c->getID()]['total_credit'];
                    }else{
                        $coa_push_list_total['credit'] = $coa_array[$c->getID()]['total_credit'];
                    }

                    $ending = $coa_array[$c->getID()]['total_debit'] - $coa_array[$c->getID()]['total_credit'];
                    $clist['ending'] = $e + $ending;

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['ending'] = ($e + $ending);
                    }
                    
                    $clist['beginning'] = $e + $ending;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['beginning'] = ($e + $ending);
                    }

                    $e = $e + $ending;
                }else{
                    $clist['debit'] = 0;
                    $clist['credit'] = 0;
                    $clist['ending'] = $e;

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += 0;
                    }else{
                        $coa_push_list_total['debit'] = 0;
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += 0;
                    }else{
                        $coa_push_list_total['credit'] = 0;
                    }

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += $e;
                    }else{
                        $coa_push_list_total['ending'] = $e;
                    }

                    $clist['beginning'] = $e;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += $e;
                    }else{
                        $coa_push_list_total['beginning'] = $e;
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
                if(isset($charts_of_account[$acc->getCode()]['ending']))
                    $ending = $charts_of_account[$acc->getCode()]['ending'];

                $liab_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                $liab_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $ending;
                $liab_total += $ending; 
            }
        }  
        $liab_main['total'] = $liab_total;
        
        $capital_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_CAPITAL]);
        $capital_total = 0;
        foreach ($capital_accounts as $as) {
            $capital_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            $coa_push_list_total = [];
            foreach ($main_accounts as $c) {
                $clist = [];
                $last_month = new DateTime($month.'/'.'01/'.$year);
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

                $clist['ending_beginning'] = $e;
                if (isset($coa_push_list_total['ending_beginning'])) {
                    $coa_push_list_total['ending_beginning'] += $e;
                }else{
                    $coa_push_list_total['ending_beginning'] = $e;
                }
                if(isset($coa_array[$c->getID()])) {
                    $clist['debit'] = $coa_array[$c->getID()]['total_debit'];
                    $clist['credit'] = $coa_array[$c->getID()]['total_credit'];

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += $coa_array[$c->getID()]['total_debit'];
                    }else{
                        $coa_push_list_total['debit'] = $coa_array[$c->getID()]['total_debit'];
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += $coa_array[$c->getID()]['total_credit'];
                    }else{
                        $coa_push_list_total['credit'] = $coa_array[$c->getID()]['total_credit'];
                    }

                    $ending = $coa_array[$c->getID()]['total_debit'] - $coa_array[$c->getID()]['total_credit'];
                    $clist['ending'] = $e + $ending;

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['ending'] = ($e + $ending);
                    }
                    
                    $clist['beginning'] = $e + $ending;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += ($e + $ending);
                    }else{
                        $coa_push_list_total['beginning'] = ($e + $ending);
                    }

                    $e = $e + $ending;
                }else{
                    $clist['debit'] = 0;
                    $clist['credit'] = 0;
                    $clist['ending'] = $e;

                    if (isset($coa_push_list_total['debit'])) {
                        $coa_push_list_total['debit'] += 0;
                    }else{
                        $coa_push_list_total['debit'] = 0;
                    }

                    if (isset($coa_push_list_total['credit'])) {
                        $coa_push_list_total['credit'] += 0;
                    }else{
                        $coa_push_list_total['credit'] = 0;
                    }

                    if (isset($coa_push_list_total['ending'])) {
                        $coa_push_list_total['ending'] += $e;
                    }else{
                        $coa_push_list_total['ending'] = $e;
                    }

                    $clist['beginning'] = $e;
                    if (isset($coa_push_list_total['beginning'])) {
                        $coa_push_list_total['beginning'] += $e;
                    }else{
                        $coa_push_list_total['beginning'] = $e;
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
                if(isset($charts_of_account[$acc->getCode()]['ending']))
                    $ending = $charts_of_account[$acc->getCode()]['ending'];

                $capital_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getName();
                $capital_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $ending;
                $capital_total += $ending; 
            }
        }  
        $capital_main['total'] = $capital_total;

        $list['assets'] = $assets_main;
        $list['liability'] = $liab_main;
        $list['capital'] = $capital_main;

        return $list;
    }
}