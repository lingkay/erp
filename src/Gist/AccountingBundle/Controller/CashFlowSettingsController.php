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
use Gist\AccountingBundle\Entity\CashFlowSettings;
use Gist\AccountingBundle\Controller\TrialBalanceController;

use DateTime;
use SplFileObject;
use LimitIterator;

class CashFlowSettingsController extends TrialBalanceController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_cf_settings';
        $this->title = 'Cash Flow Settings';
        $this->list_title = 'Cash Flow Settings';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:CashFlowSettings:index.html.twig';

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

            $cfs = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findAll();
            foreach ($cfs as $cf) {
                $em->remove($cf);
            }
            $em->flush();

            if(isset($data['accounts_receivable'])) {
                foreach ($data['accounts_receivable'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_AR)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['pre_expenses'])) {
                foreach ($data['pre_expenses'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_PE)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['accounts_payable'])) {
                foreach ($data['accounts_payable'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_AP)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['dra'])) {
                foreach ($data['dra'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_DRA)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['interest_income'])) {
                foreach ($data['interest_income'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_II)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['ibc'])) {
                foreach ($data['ibc'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_IBC)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }
            
            if(isset($data['depreciation'])) {
                foreach ($data['depreciation'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_DEP)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['iaa'])) {
                foreach ($data['iaa'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_IA)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['faa'])) {
                foreach ($data['faa'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_FA)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }

            if(isset($data['ca'])) {
                foreach ($data['ca'] as $key => $account) {
                    $id = $am->findChartOfAccount($account);
                    $assets = new CashFlowSettings();
                    $assets->setAccount($id)
                           ->setType(CashFlowSettings::TYPE_CA)
                           ->setUserCreate($this->getUser());
                    $em->persist($assets);
                }
            }
            $em->flush();

            $this->addFlash('success', $this->title . ' updated successfully.');
            return $this->redirect($this->generateUrl('gist_cf_settings_index'));
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error',$e->getMessage());
         
            $this->addFlash('error', 'Database error occurred. Possible duplicate.');
         
            error_log($e->getMessage());
             return $this->addError($obj);
        }
        catch (DBALException $e)
        {
             $this->addFlash('error',$e->getMessage());
         
            $this->addFlash('error', 'Database error occurred. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }
        catch (\Exception $e) {
            $this->addFlash('error',$e->getMessage());
            return $this->addError($obj);
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
        $am = $this->get('gist_accounting');

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
        
        $params['account_opts'] = $am->getChartOfAccountOptions();
        $params['ar_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_AR);
        $params['pe_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_PE);
        $params['ap_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_AP);
        $params['dra_opts_selected'] = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_DRA);
        $params['ii_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_II);
        $params['ibc_opts_selected'] = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_IBC);
        $params['dep_opts_selected'] = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_DEP);
        $params['ia_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_IA);
        $params['fa_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_FA);
        $params['ca_opts_selected']  = $am->findCashFlowSettingsByType(CashFlowSettings::TYPE_CA);

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
        $date_array = ['Proposed Profit or Loss'];
        foreach ($month_year as $key => $m) {
            $date = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $date = $date->format(' F Y');
            $date_array[] = $date;
        }

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["Cosmetigroup Int'l Corporation"]);
        fputcsv($file, $date_array);
        fputcsv($file, []);
        fputcsv($file, ["Sales"]);

        $gross_profit = 0;
        foreach ($bs['netsales_main_sales'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                foreach ($netsales['accounts'] as $key => $a) {
                    if (isset($a['type']) and $a['type'] == 'sales') {
                        unset($a['type']);
                        fputcsv($file, $a);
                    }
                }
            }
        }

        $sales_total = ['TOTAL SALES'];
        foreach ($bs['netsales']['nsale_total'] as $t){
            if (isset($t['sales'])) {
                $sales_total[] = $t['sales'];
            }else{
                $sales_total[] = 0;
            }
        }

        fputcsv($file, $sales_total);
        fputcsv($file, []);

        foreach ($bs['netsales_main_revenue'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                foreach ($netsales['accounts'] as $key => $a) {
                    if (isset($a['type']) and $a['type'] == 'revenue') {
                        unset($a['type']);
                        fputcsv($file, $a);
                    }
                }
            }
        }

        $revenue_total = ['TOTAL REVENUE'];
        foreach ($bs['netsales']['nrevenue_total'] as $t){
            if (isset($t['revenue'])) {
                $revenue_total[] = $t['revenue'];
            }else{
                $revenue_total[] = 0;
            }
        }
        fputcsv($file, $revenue_total);

        $netsales_total = ['NET REVENUE'];
        foreach ($bs['netsales']['total'] as $t){
            $netsales_total[] = $t;
        }
        fputcsv($file, $netsales_total);
        fputcsv($file, []);
        
        fputcsv($file, ["Cost of Sales"]);
        foreach ($bs['cos'] as $cos){
            // fputcsv($file, [$cos['name']]);
            if (isset($cos['accounts'])) {
                foreach ($cos['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        $cos_total = ['TOTAL COST OF SALES'];
        foreach ($bs['cos']['total'] as $t){
            $cos_total[] = $t;
        }
        fputcsv($file, $cos_total);
        fputcsv($file, []);

        $gross_profit = ['GROSS PROFIT'];
        foreach ($bs['cos']['total'] as $key => $t){
            $gross_profit[] = $bs['netsales']['total'][$key] - $t;
        }

        fputcsv($file, $gross_profit);
        fputcsv($file, []);

        fputcsv($file, ["Cost of Labor"]);
        foreach ($bs['opex'] as $opex){
            // fputcsv($file, [$opex['name']]);
            if (isset($opex['accounts'])) {
                foreach ($opex['accounts'] as $l) {
                    fputcsv($file, $l);
                }
            }
        }

        $opex_total = ['TOTAL PAYROLL COST'];
        foreach ($bs['opex']['total'] as $key => $t){
            $opex_total[] = $t;
        }
        fputcsv($file, $opex_total);

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
                    'total_debit' => $c->getDebit(),
                    'total_credit' => $c->getCredit(),
                ];
            }
        }

        $netsales_main_sales = [];
        $netsales_main_revenue = [];
        $cos_main = [];
        $opex_main = [];

        $netsales_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_NET_SALES]);
        $netsales_accounts_revenue = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_NET_REVENUE]);

        // for sales
        foreach ($netsales_accounts as $as) {
            $netsales_main_sales[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $netsales_main_sales[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
                    $debit = 0;
                    $credit = 0;
                    if(isset($coa_array[$acc->getID()][$m]['total_debit']))
                        $debit = $coa_array[$acc->getID()][$m]['total_debit'];
                    if(isset($coa_array[$acc->getID()][$m]['total_credit']))
                        $credit = $coa_array[$acc->getID()][$m]['total_credit'];
                    $total = $debit - $credit;

                    $netsales_main_sales[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $total;
                    $netsales_main_sales[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'sales';

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
        }  

        // for revenue
        foreach ($netsales_accounts_revenue as $as) {
            $netsales_main_revenue[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $netsales_main_revenue[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
                    $debit = 0;
                    $credit = 0;
                    if(isset($coa_array[$acc->getID()][$m]['total_debit']))
                        $debit = $coa_array[$acc->getID()][$m]['total_debit'];
                    if(isset($coa_array[$acc->getID()][$m]['total_credit']))
                        $credit = $coa_array[$acc->getID()][$m]['total_credit'];
                    $total = $debit - $credit;

                    $netsales_main_revenue[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $total;
                    $netsales_main_revenue[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'revenue';
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
        }  

        // reloop for ui table
        $n_total = [];
        foreach ($nsale_total as  $nt) {
            $n_total[] = $nt;
        }

        // reloop for ui table
        $n_total_r = [];
        foreach ($nrevenue_total as  $nt) {
            $n_total_r[] = $nt;
        }

        // reloop for ui table
        $ns_total = [];
        foreach ($netsales_total as  $nt) {
            $ns_total[] = $nt;
        }

        $netsales_main['total'] = $ns_total;
        $netsales_main['nsale_total'] = $n_total;
        $netsales_main['nrevenue_total'] = $n_total_r;

        $cos_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_COS]);
        
        foreach ($cos_accounts as $as) {
            $cos_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $cos_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
                    $debit = 0;
                    $credit = 0;
                    if(isset($coa_array[$acc->getID()][$m]['total_debit']))
                        $debit = $coa_array[$acc->getID()][$m]['total_debit'];
                    if(isset($coa_array[$acc->getID()][$m]['total_credit']))
                        $credit = $coa_array[$acc->getID()][$m]['total_credit'];
                    $total = $debit - $credit;

                    $cos_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $total;

                    if (isset($cos_total[$m])) {
                        $cos_total[$m] += $total; 
                    }else{
                        $cos_total[$m] = $total; 
                    }
                }
            }
        }  
        // reloop for ui table
        $cs_total = [];
        foreach ($cos_total as  $cs) {
            $cs_total[] = $cs;
        }

        $cos_main['total'] = $cs_total;

        $opex_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_OPEX]);

        foreach ($opex_accounts as $as) {
            $opex_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $opex_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
                    $debit = 0;
                    $credit = 0;
                    if(isset($coa_array[$acc->getID()][$m]['total_debit']))
                        $debit = $coa_array[$acc->getID()][$m]['total_debit'];
                    if(isset($coa_array[$acc->getID()][$m]['total_credit']))
                        $credit = $coa_array[$acc->getID()][$m]['total_credit'];
                    $total = $debit - $credit;

                    $opex_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $total;

                    if (isset($opex_total[$m])) {
                        $opex_total[$m] += $total; 
                    }else{
                        $opex_total[$m] = $total; 
                    }
                }
            }
        }  

        // reloop for ui table
        $o_total = [];
        foreach ($opex_total as  $ot) {
            $o_total[] = $ot;
        }

        $opex_main['total'] = $o_total;

        $list['netsales'] = $netsales_main;
        $list['netsales_main_sales'] = $netsales_main_sales;
        $list['netsales_main_revenue'] = $netsales_main_revenue;
        $list['cos'] = $cos_main;
        $list['opex'] = $opex_main;

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
        foreach ($month_year as $key => $m) {
            $date = new DateTime(substr($m, 0,2).'/'.'01/'.substr($m, 2));
            $date = $date->format(' F Y');
            $date_array[] = $date;
        }

        $array['pl'] = $pl; 
        $array['date_array'] = $date_array; 
        $array['month_year'] = $month_year; 

        return new JsonResponse($array);
    }
}