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
use Gist\AccountingBundle\Entity\ProfitAndLossSettings;
use Gist\AccountingBundle\Controller\TrialBalanceController;

use DateTime;
use SplFileObject;
use LimitIterator;

class ProfitAndLossController extends TrialBalanceController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_pl';
        $this->title = 'Profit and Loss Sheet';
        $this->list_title = 'Profit and Loss Sheet';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:ProfitAndLoss:index.html.twig';

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
        $filename = 'profit_and_loss_sheet.csv';
        // $tb = $this->getTrialBalanceData($data['date_from'], $data['date_to']);
        $bs = $this->getProfitAndLossData($data['date_from'], $data['date_to']);

        $month_year = $this->getMonthYearArray($data['date_from'], $data['date_to']);
        $date_array = ["",'Proposed Profit or Loss'];
        $below_text = ["",''];
        foreach ($month_year as $key => $m) {
            $date = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $date = $date->format(' F Y');
            $date_array[] = $date;
            $date_array[] = '';
            $below_text[] = 'In Figures';
            $below_text[] = 'In %';
        }

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["","Cosmetigroup Int'l Corporation"]);
        fputcsv($file, $date_array);
        fputcsv($file, $below_text);
        fputcsv($file, ["Code","Sales"]);

        $gross_profit = 0;
        foreach ($bs['netsales_main_sales'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                if (isset($netsales['accounts']['type']) and $netsales['accounts']['type'] == 'sales') {
                    unset($netsales['accounts']['type']);
                    fputcsv($file, $netsales['accounts']);
                }
            }
        }

        $sales_total = ['','TOTAL SALES'];
        foreach ($bs['netsales']['nsale_total'] as $t){
            if (isset($t['sales'])) {
                $sales_total[] = $t['sales'];
            }else{
                $sales_total[] = 0;
            }
                $sales_total[] = '';
        }

        fputcsv($file, $sales_total);
        fputcsv($file, []);

        foreach ($bs['netsales_main_revenue'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                if (isset($netsales['accounts']['type']) and $netsales['accounts']['type'] == 'revenue') {
                    unset($netsales['accounts']['type']);
                    fputcsv($file, $netsales['accounts']);
                }
            }
        }

        $revenue_total = ['','TOTAL REVENUE'];
        foreach ($bs['netsales']['nrevenue_total'] as $t){
            if (isset($t['revenue'])) {
                $revenue_total[] = $t['revenue'];
            }else{
                $revenue_total[] = 0;
            }
            $revenue_total[] = '';
        }
        fputcsv($file, $revenue_total);
        fputcsv($file, ['','Sales Return, Sales Discount and other Allowances']);

        foreach ($bs['netsales_main_sales_return'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                if (isset($netsales['accounts']['type']) and $netsales['accounts']['type'] == 'sales_return') {
                    unset($netsales['accounts']['type']);
                    fputcsv($file, $netsales['accounts']);
                }
            }
        }

        $sales_return_total = ['','TOTAL SALES RETURN, ALLOWANCES AND DISCOUNTS'];
        if (isset($bs['netsales']['nsale_return_total'])) {
            foreach ($bs['netsales']['nsale_return_total'] as $t){
                if (isset($t['sales_return'])) {
                    $sales_return_total[] = $t['sales_return'];
                }else{
                    $sales_return_total[] = 0;
                }
                $sales_return_total[] = '';
            }
        }
        fputcsv($file, $sales_return_total);
        fputcsv($file, []);

        $netsales_total = ['','NET REVENUE'];
        foreach ($bs['netsales']['total'] as $t){
            $netsales_total[] = $t;
            $netsales_total[] = '';
        }
        fputcsv($file, $netsales_total);
        fputcsv($file, []);
        
        fputcsv($file, ["","Cost of Sales"]);
        foreach ($bs['cos'] as $cos){
            if (isset($cos['accounts'])) {
                fputcsv($file, $cos['accounts']);
            }
        }

        $cos_total = ['','TOTAL COST OF SALES'];
        foreach ($bs['cos']['total'] as $t){
            $cos_total[] = $t;
            $cos_total[] = '';
        }
        fputcsv($file, $cos_total);
        fputcsv($file, []);

        $gross_profit = ['','GROSS PROFIT'];
        foreach ($bs['cos']['total'] as $key => $t){
            $gross_profit[] = $bs['netsales']['total'][$key] - $t;
            $gross_profit[] = '';
        }

        fputcsv($file, $gross_profit);
        fputcsv($file, []);

        fputcsv($file, ["","Cost of Labor"]);
        foreach ($bs['col'] as $col){
            // fputcsv($file, [$col['name']]);
            if (isset($col['accounts'])) {
                fputcsv($file, $col['accounts']);
            }
        }

        $col_total = ['','TOTAL PAYROLL COST'];
        foreach ($bs['col']['total'] as $key => $t){
            $col_total[] = $t;
            $col_total[] = '';
        }
        fputcsv($file, $col_total);
        fputcsv($file, []);

        fputcsv($file, ["","Other Employee Cost"]);
        foreach ($bs['eoc'] as $eoc){
            // fputcsv($file, [$eoc['name']]);
            if (isset($eoc['accounts'])) {
                fputcsv($file, $eoc['accounts']);
            }
        }

        $eoc_total = ['','TOTAL OTHER EMPLOYEE COST'];
        $tpeoc_total = ['','TOTAL PAYROLL AND OTHER EMPLOYEE COST'];
        foreach ($bs['eoc']['total'] as $key => $t){
            $eoc_total[] = $t;
            $eoc_total[] = '';
            $tpeoc_total[] = $bs['col']['total'][$key] + $t;
            $tpeoc_total[] = '';
        }

        fputcsv($file, $eoc_total);
        fputcsv($file, $tpeoc_total);
        fputcsv($file, []);
        fputcsv($file, ["","Operating Expenses"]);
        fputcsv($file, ["","Controllable Expenses"]);
        fputcsv($file, ["Utility Cost"]);
        foreach ($bs['uc'] as $uc){
            // fputcsv($file, [$uc['name']]);
            if (isset($uc['accounts'])) {
                fputcsv($file, $uc['accounts']);
            }
        }
        fputcsv($file, ["Telephone Charges and DSL Connection"]);
        foreach ($bs['tcdc'] as $tcdc){
            // fputcsv($file, [$tcdc['name']]);
            if (isset($tcdc['accounts'])) {
                fputcsv($file, $tcdc['accounts']);
            }
        }
        fputcsv($file, ["Supplies"]);
        foreach ($bs['supp'] as $supp){
            // fputcsv($file, [$supp['name']]);
            if (isset($supp['accounts'])) {
                fputcsv($file, $supp['accounts']);
            }
        }
        fputcsv($file, ["Repair and Maintenance"]);
        foreach ($bs['rm'] as $rm){
            // fputcsv($file, [$rm['name']]);
            if (isset($rm['accounts'])) {
                fputcsv($file, $rm['accounts']);
            }
        }
        fputcsv($file, ["Freight Out and Others"]);
        foreach ($bs['foo'] as $foo){
            // fputcsv($file, [$foo['name']]);
            if (isset($foo['accounts'])) {
                fputcsv($file, $foo['accounts']);
            }
        }
        fputcsv($file, ["Other External Expenditures"]);
        foreach ($bs['oee'] as $oee){
            // fputcsv($file, [$oee['name']]);
            if (isset($oee['accounts'])) {
                fputcsv($file, $oee['accounts']);
            }
        }

        $controllable_total = ['','TOTAL CONTROLLABLE COST'];
        foreach ($bs['controllable_total']['total'] as $key => $t){
            $controllable_total[] = $t;
            $controllable_total[] = '';
        }
        fputcsv($file, $controllable_total);
        fputcsv($file, []);
        fputcsv($file, ['','Non Controllable Expenses']);
        fputcsv($file, ["Occupancy Cost"]);
        foreach ($bs['oc'] as $oc){
            // fputcsv($file, [$oc['name']]);
            if (isset($oc['accounts'])) {
                fputcsv($file, $oc['accounts']);
            }
        }
        fputcsv($file, ["Depreciation and Amortization"]);
        foreach ($bs['da'] as $da){
            // fputcsv($file, [$da['name']]);
            if (isset($da['accounts'])) {
                fputcsv($file, $da['accounts']);
            }
        }

        $non_controllable_total = ['','TOTAL NON CONTROLLABLE COST'];
        $opex_total = ['','TOTAL OPERATING EXPENSES'];
        foreach ($bs['non_controllable_total']['total'] as $key => $t){
            $non_controllable_total[] = $t;
            $non_controllable_total[] = '';
            $opex_total[] = $bs['controllable_total']['total'][$key] + $t;
            $opex_total[] = '';
        }
        fputcsv($file, $non_controllable_total);
        fputcsv($file, $opex_total);
        fputcsv($file, []);
        fputcsv($file, ['Marketing Advertising and Branding']);
        foreach ($bs['mab'] as $mab){
            // fputcsv($file, [$mab['name']]);
            if (isset($mab['accounts'])) {
                fputcsv($file, $mab['accounts']);
            }
        }

        $mab_total = ['','TOTAL SALES AND MARKETING'];
        foreach ($bs['mab']['total'] as $key => $t){
            $mab_total[] = $t;
            $mab_total[] = '';
        }
        fputcsv($file, $mab_total);

        fputcsv($file, ['Other income and charges']);
        foreach ($bs['oic'] as $oic){
            // fputcsv($file, [$oic['name']]);
            if (isset($oic['accounts'])) {
                fputcsv($file, $oic['accounts']);
            }
        }

        $oic_total = ['','TOTAL OTHER INCOME/(CHARGES)'];
        foreach ($bs['oic']['total'] as $key => $t){
            $oic_total[] = $t;
            $oic_total[] = '';
        }
        fputcsv($file, $oic_total);
        fputcsv($file, []);
        $tce_total = ['','TOTAL COST AND EXPENSES'];
        foreach ($bs['oic']['total'] as $key => $t){
            $cos = 0;
            $refund = 0;
            $col = 0;
            $opex = 0;
            $mab = 0;
            $oic = 0;
            if (isset($bs['cos']['total'][$key]))
                $cos = $bs['cos']['total'][$key];
            if (isset($bs['netsales']['nrevenue_total'][$key]['revenue']))
                $refund = $bs['netsales']['nrevenue_total'][$key]['revenue'];
            if (isset($bs['col']['total'][$key]))
                $col = $bs['col']['total'][$key];
            if (isset($bs['non_controllable_total']['total'][$key]) && isset($bs['controllable_total']['total'][$key]))
                $opex = $bs['non_controllable_total']['total'][$key] + $bs['controllable_total']['total'][$key];
            if (isset($bs['mab']['total'][$key]))
                $mab = $bs['mab']['total'][$key];

            $tce_total[] = $cos + $refund + $col + $opex + $mab + $t;
            $tce_total[] = '';
        }
        fputcsv($file, $tce_total);
        fputcsv($file, []);
        $ilop_total = ['','INCOME OR LOSS FROM OPERATIONS'];
        $nibt_total = ['','NET INCOME/(LOSS) BEFORE TAX'];
        foreach ($bs['oic']['total'] as $key => $t){
            $cos = 0;
            $refund = 0;
            $col = 0;
            $opex = 0;
            $mab = 0;
            $oic = 0;
            $nr = 0;
            if (isset($bs['cos']['total'][$key]))
                $cos = $bs['cos']['total'][$key];
            if (isset($bs['netsales']['nrevenue_total'][$key]['revenue']))
                $refund = $bs['netsales']['nrevenue_total'][$key]['revenue'];
            if (isset($bs['col']['total'][$key]))
                $col = $bs['col']['total'][$key];
            if (isset($bs['non_controllable_total']['total'][$key]) && isset($bs['controllable_total']['total'][$key]))
                $opex = $bs['non_controllable_total']['total'][$key] + $bs['controllable_total']['total'][$key];
            if (isset($bs['mab']['total'][$key]))
                $mab = $bs['mab']['total'][$key];
            if (isset($bs['netsales']['total'][$key]))
                $nr = $bs['netsales']['total'][$key];

            
            $total = $cos + $refund + $col + $opex + $mab + $t;

            $ilop_total[] = $nr - $total;
            $ilop_total[] = '';
            $nibt_total[] = $nr - $total;
            $nibt_total[] = '';
        }
        fputcsv($file, $ilop_total);
        fputcsv($file, []);
        fputcsv($file, $nibt_total);
        fputcsv($file, []);

        foreach ($bs['nit'] as $nit){
            // fputcsv($file, [$nit['name']]);
            if (isset($nit['accounts'])) {
                fputcsv($file, $nit['accounts']);
            }
        }

        $nit_total = ['','NET INCOME AFTER TAX'];
        foreach ($bs['oic']['total'] as $key => $t){
            $cos = 0;
            $refund = 0;
            $col = 0;
            $opex = 0;
            $mab = 0;
            $oic = 0;
            $nr = 0;
            $nit = 0;
            if (isset($bs['cos']['total'][$key]))
                $cos = $bs['cos']['total'][$key];
            if (isset($bs['netsales']['nrevenue_total'][$key]['revenue']))
                $refund = $bs['netsales']['nrevenue_total'][$key]['revenue'];
            if (isset($bs['col']['total'][$key]))
                $col = $bs['col']['total'][$key];
            if (isset($bs['non_controllable_total']['total'][$key]) && isset($bs['controllable_total']['total'][$key]))
                $opex = $bs['non_controllable_total']['total'][$key] + $bs['controllable_total']['total'][$key];
            if (isset($bs['mab']['total'][$key]))
                $mab = $bs['mab']['total'][$key];
            if (isset($bs['netsales']['total'][$key]))
                $nr = $bs['netsales']['total'][$key];
            if (isset($bs['nit']['total'][$key]))
                $nit = $bs['nit']['total'][$key];
            
            $total = $cos + $refund + $col + $opex + $mab + $t;

            $nit_total[] = ($nr - $total) + $nit;
            $nit_total[] = '';
        }
        fputcsv($file, $nit_total);

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    protected function getProfitAndLossData($from, $to)
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
                    'total_debit' => (float)$c->getDebit(),
                    'total_credit' => (float)$c->getCredit(),
                ];
            }
        }

        $netsales_main_sales = [];
        $netsales_main_revenue = [];
        $netsales_main_sales_return = [];
        $cos_main = [];
        $col_main = [];
        $oec_main = [];
        $uc_main = [];
        $tcdc_main = [];
        $supp_main = [];
        $rm_main = [];
        $foo_main = [];
        $oee_main = [];
        $oc_main = [];
        $da_main = [];
        $mab_main = [];
        $oic_main = [];
        $nit_main = [];

        $netsales_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_SALES]);
        $netsales_accounts_revenue = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_RETURN]);
        $netsales_accounts_sales_return = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_SR]);

        // for sales
        foreach ($netsales_accounts as $as) {
            $netsales_main_sales[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $netsales_main_sales[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
          
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $netsales_main_sales[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $netsales_main_sales[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';
                $netsales_main_sales[$as->getAccount()->getID()]['accounts']['type'] = 'sales';

                if (isset($nsale_total[$m]['sales'])) {
                    $nsale_total[$m]['sales'] += $total; 
                }else{
                    $nsale_total[$m]['sales'] = $total; 
                }

                if (isset($netsales_total[$m])) {
                    $netsales_total[$m] += $total; 
                }else{
                    $netsales_total[$m] = $total; 
                }
            }
        }  

        // for revenue
        foreach ($netsales_accounts_revenue as $as) {
            $netsales_main_revenue[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $netsales_main_revenue[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $netsales_main_revenue[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $netsales_main_revenue[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';
                $netsales_main_revenue[$as->getAccount()->getID()]['accounts']['type'] = 'revenue';
                if (isset($nsale_total[$m]['revenue'])) {
                    $nrevenue_total[$m]['revenue'] += $total; 
                }else{
                    $nrevenue_total[$m]['revenue'] = $total; 
                }

                if (isset($netsales_total[$m])) {
                    $netsales_total[$m] += $total; 
                }else{
                    $netsales_total[$m] = $total; 
                }
            }
        }  

        // for sales return
        foreach ($netsales_accounts_sales_return as $as) {
            $netsales_main_sales_return[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $netsales_main_sales_return[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $netsales_main_sales_return[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $netsales_main_sales_return[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';
                $netsales_main_sales_return[$as->getAccount()->getID()]['accounts']['type'] = 'sales_return';
                if (isset($nsales_return_total[$m]['sales_return'])) {
                    $nsales_return_total[$m]['sales_return'] += $total; 
                }else{
                    $nsales_return_total[$m]['sales_return'] = $total; 
                }

                if (isset($netsales_total[$m])) {
                    $netsales_total[$m] += $total; 
                }else{
                    $netsales_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $n_total = [];
        if (isset($nsale_total)) {
            foreach ($nsale_total as  $nt) {
                $n_total[] = $nt;
            }
        }

        // reloop for ui table
        $n_total_r = [];
        if (isset($nrevenue_total)) {
            foreach ($nrevenue_total as  $nt) {
                $n_total_r[] = $nt;
            }
        }

        // reloop for ui table
        $n_total_sr = [];
        if (isset($nsales_return_total)) {
            foreach ($nsales_return_total as  $nt) {
                $n_total_sr[] = $nt;
            }
        }

        // reloop for ui table
        $ns_total = [];
        if (isset($netsales_total)) {
            foreach ($netsales_total as  $nt) {
                $ns_total[] = $nt;
            }
        }

        $netsales_main['total'] = $ns_total;
        $netsales_main['nsale_total'] = $n_total;
        $netsales_main['nrevenue_total'] = $n_total_r;
        $netsales_main['nsale_return_total'] = $n_total_sr;

        $cos_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_COS]);
        
        foreach ($cos_accounts as $as) {
            $cos_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $cos_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $cos_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $cos_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($cos_total[$m])) {
                    $cos_total[$m] += $total; 
                }else{
                    $cos_total[$m] = $total; 
                }
            }
        }  
        // reloop for ui table
        $cs_total = [];
        foreach ($cos_total as  $cs) {
            $cs_total[] = $cs;
        }

        $cos_main['total'] = $cs_total;

        // Cost of Labor
        $col_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_COL]);

        foreach ($col_accounts as $as) {
            $col_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $col_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $col_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $col_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($col_total[$m])) {
                    $col_total[$m] += $total; 
                }else{
                    $col_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $o_total = [];
        foreach ($col_total as  $ot) {
            $o_total[] = $ot;
        }

        $col_main['total'] = $o_total;

        // Other Employee Cost
        $oec_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_OEC]);

        foreach ($oec_accounts as $as) {
            $eoc_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $eoc_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $eoc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $eoc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($eoc_total[$m])) {
                    $eoc_total[$m] += $total; 
                }else{
                    $eoc_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $eoc_ttl = [];
        if (isset($eoc_total)) {
            foreach ($eoc_total as  $ot) {
                $eoc_ttl[] = $ot;
            }
        }

        $eoc_main['total'] = $eoc_ttl;

        // Operation Expenses
        // Controllable Expenses
        // Utility Cost
        $uc_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_UC]);

        foreach ($uc_accounts as $as) {
            $uc_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $uc_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $uc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $uc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  

        // Telephone Charges and DSL Connection
        $tcdc_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_TCDC]);

        foreach ($tcdc_accounts as $as) {
            $tcdc_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $tcdc_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $tcdc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $tcdc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  

        // Supplies
        $supp_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_SUPP]);
        foreach ($supp_accounts as $as) {
            $supp_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $supp_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $supp_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $supp_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  
        
        // Repair and Maintenance
        $rm_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_RM]);

        foreach ($rm_accounts as $as) {
            $rm_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $rm_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $rm_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $rm_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  

        // Freight Out and Others
        $foo_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_FOO]);

        foreach ($foo_accounts as $as) {
            $foo_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $foo_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $foo_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $foo_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  

        // Other External Expenditures
        $oee_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_OEE]);

        foreach ($oee_accounts as $as) {
            $oee_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $oee_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $oee_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $oee_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($controllable_total[$m])) {
                    $controllable_total[$m] += $total; 
                }else{
                    $controllable_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $controllable_ttl = [];
        if (isset($controllable_total)) {
            foreach ($controllable_total as  $ot) {
                $controllable_ttl[] = $ot;
            }
        }

        $controllable_main['total'] = $controllable_ttl;
        
        // Non Controllable Expenses
        // Occupancy Cost
        $oc_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_OC]);

        foreach ($oc_accounts as $as) {
            $oc_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $oc_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $oc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $oc_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($non_controllable_total[$m])) {
                    $non_controllable_total[$m] += $total; 
                }else{
                    $non_controllable_total[$m] = $total; 
                }
            }
        }  

        // Depreciation and Amortization
        $da_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_DA]);

        foreach ($da_accounts as $as) {
            $da_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $da_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $da_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $da_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($non_controllable_total[$m])) {
                    $non_controllable_total[$m] += $total; 
                }else{
                    $non_controllable_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $non_controllable_ttl = [];
        if (isset($non_controllable_total)) {
            foreach ($non_controllable_total as  $ot) {
                $non_controllable_ttl[] = $ot;
            }
        }

        $non_controllable_main['total'] = $non_controllable_ttl;

        // Marketing Advertising and Branding
        $mab_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_MAB]);

        foreach ($mab_accounts as $as) {
            $mab_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $mab_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $mab_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $mab_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($mab_total[$m])) {
                    $mab_total[$m] += $total; 
                }else{
                    $mab_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $mab_ttl = [];
        if (isset($mab_total)) {
            foreach ($mab_total as  $ot) {
                $mab_ttl[] = $ot;
            }
        }

        $mab_main['total'] = $mab_ttl;

        // Other Income and Charges
        $oic_accounts = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_OIC]);

        foreach ($oic_accounts as $as) {
            $oic_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $oic_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $oic_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $oic_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($oic_total[$m])) {
                    $oic_total[$m] += $total; 
                }else{
                    $oic_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $oic_ttl = [];
        if (isset($oic_total)) {
            foreach ($oic_total as  $ot) {
                $oic_ttl[] = $ot;
            }
        }

        $oic_main['total'] = $oic_ttl;

        // Net Income and Tax
        $nit_accounts  = $em->getRepository('GistAccountingBundle:ProfitAndLossSettings')->findBy(['type' => ProfitAndLossSettings::TYPE_NIT]);

        foreach ($nit_accounts as $as) {
            $nit_main[$as->getAccount()->getID()]['accounts']['code'] = $as->getAccount()->getCode();
            $nit_main[$as->getAccount()->getID()]['accounts']['name'] = $as->getAccount()->getName();
            foreach($month_year as $key => $m) {
                $debit = 0;
                $credit = 0;
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_debit']))
                    $debit = $coa_array[$as->getAccount()->getID()][$m]['total_debit'];
                if(isset($coa_array[$as->getAccount()->getID()][$m]['total_credit']))
                    $credit = $coa_array[$as->getAccount()->getID()][$m]['total_credit'];
                $total = $debit - $credit;

                $nit_main[$as->getAccount()->getID()]['accounts']['total_'.$m.''] = $total;
                $nit_main[$as->getAccount()->getID()]['accounts']['total_'.$m.'space'] = '';

                if (isset($nit_total[$m])) {
                    $nit_total[$m] += $total; 
                }else{
                    $nit_total[$m] = $total; 
                }
            }
        }  

        // reloop for ui table
        $nit_ttl = [];
        if (isset($nit_total)) {
            foreach ($nit_total as  $ot) {
                $nit_ttl[] = $ot;
            }
        }

        $nit_main['total'] = $nit_ttl;

        $list['netsales'] = $netsales_main;
        $list['netsales_main_sales'] = $netsales_main_sales;
        $list['netsales_main_revenue'] = $netsales_main_revenue;
        $list['netsales_main_sales_return'] = $netsales_main_sales_return;
        $list['cos'] = $cos_main;
        $list['col'] = $col_main;
        $list['eoc'] = $eoc_main;
        $list['uc'] = $uc_main;
        $list['tcdc'] = $tcdc_main;
        $list['supp'] = $supp_main;
        $list['rm'] = $rm_main;
        $list['foo'] = $foo_main;
        $list['oee'] = $oee_main;
        $list['controllable_total'] = $controllable_main;
        $list['oc'] = $oc_main;
        $list['da'] = $da_main;
        $list['non_controllable_total'] = $non_controllable_main;
        $list['mab'] = $mab_main;
        $list['oic'] = $oic_main;
        $list['nit'] = $nit_main;

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

    public function generateTableAction($from, $to)
    {
        $data = $this->getRequest()->request->all();
        $pl = $this->getProfitAndLossData($from, $to);

        $month_year = $this->getMonthYearArray($from, $to);
        $date_array = ['Proposed Profit or Loss'];
        $below_text = [''];
        foreach ($month_year as $key => $m) {
            $date = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $date = $date->format(' F Y');
            $date_array[] = $date;
            $date_array[] = '';
            $below_text[] = 'In Figures';
            $below_text[] = 'In %';
        }

        $array['pl'] = $pl; 
        $array['date_array'] = $date_array; 
        $array['below_text'] = $below_text; 
        $array['month_year'] = $month_year; 

        return new JsonResponse($array);
    }
}