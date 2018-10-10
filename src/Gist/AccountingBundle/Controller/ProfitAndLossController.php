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
        $bs = $this->getProfitAndLossData($data['month'], $data['year']);

        $date = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        $date = $date->format('F Y');

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["Cosmetigroup Int'l Corporation"]);
        fputcsv($file, ["Proposed Profit or Loss", " ".$date]);
        fputcsv($file, []);
        fputcsv($file, ["Sales"]);

        $sales_total = 0;
        $revenue_total = 0;
        $gross_profit = 0;
        foreach ($bs['netsales'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                foreach ($netsales['accounts'] as $a) {
                    if (isset($a['type']) and $a['type'] == 'sales') {
                        array_pop($a);
                        fputcsv($file, $a);
                        $sales_total += $a['total'];
                    }
                }
            }
        }
        fputcsv($file, ['TOTAL SALES',$sales_total]);
        fputcsv($file, []);

        foreach ($bs['netsales'] as $netsales){
            // fputcsv($file, [$netsales['name']]);
            if (isset($netsales['accounts'])) {
                foreach ($netsales['accounts'] as $a) {
                    if (isset($a['type']) and $a['type'] == 'revenue') {
                        array_pop($a);
                        fputcsv($file, $a);
                        $revenue_total += $a['total'];
                    }
                }
            }
        }

        fputcsv($file, ['TOTAL REVENUE',$revenue_total]);
        fputcsv($file, ['NET REVENUE',$bs['netsales']['total']]);
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

        $gross_profit =  $bs['netsales']['total'] + $bs['cos']['total'];
        fputcsv($file, ['TOTAL COST OF SALES',$bs['cos']['total']]);
        fputcsv($file, []);
        fputcsv($file, ["GROSS PROFIT", $gross_profit]);
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
        fputcsv($file, ['TOTAL PAYROLL COST',$bs['opex']['total']]);

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    protected function getProfitAndLossData($month, $year)
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

        $netsales_main = [];
        $cos_main = [];
        $opex_main = [];

        $netsales_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_NET_STALES]);
        $netsales_total = 0;
        foreach ($netsales_accounts as $as) {
            $netsales_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $debit = 0;
                $credit = 0;
                $total = 0;
                if(isset($coa_array[$acc->getID()]['total_debit']))
                    $debit = $coa_array[$acc->getID()]['total_debit'];
                if(isset($coa_array[$acc->getID()]['total_credit']))
                    $credit = $coa_array[$acc->getID()]['total_credit'];
                $total = $debit - $credit;

                $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $total;
                if ($debit == 0) {
                    $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'revenue';
                }else{
                    $netsales_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['type'] = 'sales';
                }
                $netsales_total += $total; 
            }
        }  
        $netsales_main['total'] = $netsales_total;

        $cos_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_COS]);
        $cos_total = 0;
        foreach ($cos_accounts as $as) {
            $cos_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $debit = 0;
                $credit = 0;
                $total = 0;
                if(isset($coa_array[$acc->getID()]['total_debit']))
                    $debit = $coa_array[$acc->getID()]['total_debit'];
                if(isset($coa_array[$acc->getID()]['total_credit']))
                    $credit = $coa_array[$acc->getID()]['total_credit'];
                $total = $debit - $credit;

                $cos_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                $cos_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $total;
                $cos_total += $total; 
            }
        }  
        $cos_main['total'] = $cos_total;
        
        $opex_accounts = $em->getRepository('GistAccountingBundle:TrialBalanceSettings')->findBy(['type' => TrialBalanceSettings::TYPE_OPEX]);
        $opex_total = 0;
        foreach ($opex_accounts as $as) {
            $opex_main[$as->getAccount()->getID()]['name'] = $as->getAccount()->getName();
            $main_accounts = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findBy(['main_account' => $as->getAccount()->getID()]);

            foreach ($main_accounts as $acc) {
                $debit = 0;
                $credit = 0;
                $total = 0;
                if(isset($coa_array[$acc->getID()]['total_debit']))
                    $debit = $coa_array[$acc->getID()]['total_debit'];
                if(isset($coa_array[$acc->getID()]['total_credit']))
                    $credit = $coa_array[$acc->getID()]['total_credit'];
                $total = $debit - $credit;

                $opex_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['name'] = $acc->getCode() .' '. $acc->getName();
                $opex_main[$as->getAccount()->getID()]['accounts'][$acc->getID()]['total'] = $total;
                $opex_total += $total; 
            }
        }  
        $opex_main['total'] = $opex_total;

        $list['netsales'] = $netsales_main;
        $list['cos'] = $cos_main;
        $list['opex'] = $opex_main;

        return $list;
    }
}