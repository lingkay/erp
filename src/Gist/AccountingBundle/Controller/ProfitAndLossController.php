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
        foreach ($bs['netsales'] as $netsales){
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
            $sales_total[] = $t['sales'];
        }

        fputcsv($file, $sales_total);
        fputcsv($file, []);

        foreach ($bs['netsales'] as $netsales){
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
        foreach ($bs['netsales']['nsale_total'] as $t){
            $revenue_total[] = $t['revenue'];
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
            $gross_profit[] = $bs['netsales']['total'][$key] + $t;
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

        $netsales_main = [];
        $cos_main = [];
        $opex_main = [];

        $netsales_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_NET_STALES]);

        foreach ($netsales_accounts as $as) {
            $netsales_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
                    if(isset($coa_array[$acc->getID()][$m]['total_debit']))
                        $debit = $coa_array[$acc->getID()][$m]['total_debit'];
                    if(isset($coa_array[$acc->getID()][$m]['total_credit']))
                        $credit = $coa_array[$acc->getID()][$m]['total_credit'];
                    $total = $debit - $credit;

                    $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total_'.$m.''] = $total;
                    if ($debit == 0) {
                        $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'sales';
                        if (isset($nsale_total[$m]['sales'])) {
                            $nsale_total[$m]['sales'] += $total; 
                        }else{
                            $nsale_total[$m]['sales'] = $total; 
                        }
                    }else{
                        $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'revenue';
                        if (isset($nsale_total[$m]['revenue'])) {
                            $nsale_total[$m]['revenue'] += $total; 
                        }else{
                            $nsale_total[$m]['revenue'] = $total; 
                        }
                    }

                    if (isset($netsales_total[$m])) {
                        $netsales_total[$m] += $total; 
                    }else{
                        $netsales_total[$m] = $total; 
                    }
                }
            }
        }  

        $netsales_main['total'] = $netsales_total;
        $netsales_main['nsale_total'] = $nsale_total;

        $cos_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_COS]);
        
        foreach ($cos_accounts as $as) {
            $cos_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $cos_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
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
        $cos_main['total'] = $cos_total;

        $opex_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_OPEX]);

        foreach ($opex_accounts as $as) {
            $opex_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $opex_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                foreach($month_year as $key => $m) {
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
        $opex_main['total'] = $opex_total;

        $list['netsales'] = $netsales_main;
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
}