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

class CashFlowController extends TrialBalanceController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_cf';
        $this->title = 'Cash Flow';
        $this->list_title = 'Cash Flow';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:CashFlow:index.html.twig';

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
        $filename = 'cash_flow.csv';
        // $tb = $this->getTrialBalanceData($data['date_from'], $data['date_to']);
        $cf = $this->getCashFlowData($data['month'], $data['year']);

        $date = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        $date = $date->format(' F Y');

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, ["Cosmetigroup Int'l Corp"]);
        fputcsv($file, ["Statement of Financial Position"]);
        fputcsv($file, [$date]);

        fputcsv($file, []);
        fputcsv($file, ["CASH FLOW FROM OPERATING ACTIVITIES"]);
        fputcsv($file, ["","Income before Income tax"]);
        fputcsv($file, ["","Accounts Receivable"]);
        fputcsv($file, ["","Deduct:"]);
        foreach ($cf['accounts_receivable'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }
        fputcsv($file, ["","Add:"]);
        foreach ($cf['accounts_receivable'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }

        fputcsv($file, ["","Prepaid Expenses"]);
        fputcsv($file, ["","Deduct:"]);
        foreach ($cf['prepaid_expenses'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }
        fputcsv($file, ["","Add:"]);
        foreach ($cf['prepaid_expenses'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }

        fputcsv($file, ["","Accounts Payable"]);
        fputcsv($file, ["","Add:"]);
        foreach ($cf['accounts_payable'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }
        fputcsv($file, ["","Deduct:"]);
        foreach ($cf['accounts_payable'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }

        // Gain or Loss 
        foreach ($cf['disposal_assets'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }
        foreach ($cf['disposal_assets'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }

        // Income Intereset
        foreach ($cf['interest_income'] as $ar){
            if (isset($ar['accounts'])) {
                fputcsv($file, $ar['accounts']);
            }
        }

        // Income and Other Bank Charges
        foreach ($cf['interest_and_bank'] as $ar){
            if (isset($ar['accounts'])) {
                fputcsv($file, $ar['accounts']);
            }
        }

        fputcsv($file, ["","Depreciation"]);
        fputcsv($file, ["","Add:"]);
        foreach ($cf['depreciation'] as $ar){
            if (isset($ar['accounts'])) {
                fputcsv($file, $ar['accounts']);
            }
        }

        fputcsv($file, ["NET CASH PROVIDED BY/(USED IN) OPERATING ACTIVITIES","","",$cf['operating_activities_total']]);
        fputcsv($file, []);
        fputcsv($file, []);
        fputcsv($file, []);
        fputcsv($file, ["CASH FLOW FROM INVESTING ACTIVITIES"]);
        fputcsv($file, ["","Disposal of:"]);
        fputcsv($file, ["","Add:"]);
        foreach ($cf['investing_activities'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }
        fputcsv($file, []);
        fputcsv($file, ["","Purchase/Additions to:"]);
        fputcsv($file, ["","Deduct:"]);
        foreach ($cf['investing_activities'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }
        fputcsv($file, ["NET CASH PROVIDED BY/(USED IN) INVESTING ACTIVITIES","","",$cf['investing_activities_total']]);
        fputcsv($file, []);
        fputcsv($file, []);

        fputcsv($file, ["CASH FLOW FROM OPERATING ACTIVITIES"]);
        foreach ($cf['financing_activities'] as $ar){
            if (isset($ar['accounts']['increase'])) {
                fputcsv($file, $ar['accounts']['increase']);
            }
        }
        foreach ($cf['financing_activities'] as $ar){
            if (isset($ar['accounts']['decrease'])) {
                fputcsv($file, $ar['accounts']['decrease']);
            }
        }
        fputcsv($file, ["NET CASH PROVIDED BY/(USED IN) FINANCING ACTIVITIES","","",$cf['financing_activities_total']]);
        fputcsv($file, ["NET INCREASE/(DECREASE) IN CASH AND CASH EQUIVALENTS","","",$cf['net_total']]);
        fputcsv($file, ["ADD: BEGINNING BALANCE OF CASH AND CASH EQUIVALENTS","","",""]);
        fputcsv($file, ["ENDING BALANCE OF CASH AND CASH EQUIVALENTS","","",""]);

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }

    protected function getCashFlowData($month, $year)
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
                    'total_debit' => (float)$c->getDebit(),
                    'total_credit' => (float)$c->getCredit(),
                ];
            }
        }

        $accounts_receivable = [];
        $prepaid_expenses = [];
        $accounts_payable = [];
        $depreciation = [];
        $investing_activities = [];
        $financing_activities = [];
        $disposal_assets = [];
        $interest_income = [];
        $interest_and_bank = [];
        $operating_activities_total = 0;
        $investing_activities_total = 0;
        $financing_activities_total = 0;

        $acc_receivable = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_AR]);
        foreach ($acc_receivable as $ar) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$ar->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$ar->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$ar->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$ar->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['increase']['code'] = $ar->getAccount()->getCode();
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['increase']['name'] = 'Increase in ' .$ar->getAccount()->getName();
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['decrease']['code'] = $ar->getAccount()->getCode();
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['decrease']['name'] = 'Decrease in ' .$ar->getAccount()->getName();

            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $accounts_receivable[$ar->getAccount()->getID()]['accounts']['decrease']['space'] = "";

            $operating_activities_total += $total;
        }  

        $pre_expenses = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_PE]);
        foreach ($pre_expenses as $pe) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$pe->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$pe->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$pe->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$pe->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['increase']['code'] = $pe->getAccount()->getCode();
            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['increase']['name'] = 'Increase in ' .$pe->getAccount()->getName();
            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['decrease']['code'] = $pe->getAccount()->getCode();
            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['decrease']['name'] = 'Decrease in ' .$pe->getAccount()->getName();

            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;

            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $prepaid_expenses[$pe->getAccount()->getID()]['accounts']['decrease']['space'] = "";

            $operating_activities_total += $total;
        } 

        $acc_payable = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_AP]);
        foreach ($acc_payable as $ap) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$ap->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$ap->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$ap->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$ap->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $accounts_payable[$ap->getAccount()->getID()]['accounts']['increase']['code'] = $ap->getAccount()->getCode();
            $accounts_payable[$ap->getAccount()->getID()]['accounts']['increase']['name'] = 'Increase in ' .$ap->getAccount()->getName();
            $accounts_payable[$ap->getAccount()->getID()]['accounts']['decrease']['code'] = $ap->getAccount()->getCode();
            $accounts_payable[$ap->getAccount()->getID()]['accounts']['decrease']['name'] = 'Decrease in ' .$ap->getAccount()->getName();

            $accounts_payable[$ap->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $accounts_payable[$ap->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;

            $accounts_payable[$ap->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $accounts_payable[$ap->getAccount()->getID()]['accounts']['decrease']['space'] = "";

            $operating_activities_total += $total;
        } 

        $disposals = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_DRA]);
        foreach ($disposals as $d) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$d->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$d->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$d->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$d->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $disposal_assets[$d->getAccount()->getID()]['accounts']['increase']['code'] = $d->getAccount()->getCode();
            $disposal_assets[$d->getAccount()->getID()]['accounts']['increase']['name'] = 'Gain in ' .$d->getAccount()->getName();
            $disposal_assets[$d->getAccount()->getID()]['accounts']['decrease']['code'] = $d->getAccount()->getCode();
            $disposal_assets[$d->getAccount()->getID()]['accounts']['decrease']['name'] = 'Loss in ' .$d->getAccount()->getName();

            $disposal_assets[$d->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $disposal_assets[$d->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;
            $disposal_assets[$d->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $disposal_assets[$d->getAccount()->getID()]['accounts']['decrease']['space'] = "";

            $operating_activities_total += $total;
        }  

        $int_income = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_II]);
        foreach ($int_income as $d) {
            $interest_income[$d->getAccount()->getID()]['accounts']['code'] = $d->getAccount()->getCode();
            $interest_income[$d->getAccount()->getID()]['accounts']['name'] = $d->getAccount()->getName();
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$d->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$d->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$d->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$d->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;
                
            $interest_income[$d->getAccount()->getID()]['accounts']['amount'] = $total;
            $interest_income[$d->getAccount()->getID()]['accounts']['space'] = "";

            $operating_activities_total += $total;
        } 

        $interest_ibc = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_IBC]);
        foreach ($interest_ibc as $d) {
            $interest_and_bank[$d->getAccount()->getID()]['accounts']['code'] = $d->getAccount()->getCode();
            $interest_and_bank[$d->getAccount()->getID()]['accounts']['name'] = $d->getAccount()->getName();
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$d->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$d->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$d->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$d->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;
                
            $interest_and_bank[$d->getAccount()->getID()]['accounts']['amount'] = $total;
            $interest_and_bank[$d->getAccount()->getID()]['accounts']['space'] = "";

            $operating_activities_total += $total;
        } 

        $depr = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_DEP]);
        foreach ($depr as $d) {
            $depreciation[$d->getAccount()->getID()]['accounts']['code'] = $d->getAccount()->getCode();
            $depreciation[$d->getAccount()->getID()]['accounts']['name'] = $d->getAccount()->getName();
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$d->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$d->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$d->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$d->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;
                
            $depreciation[$d->getAccount()->getID()]['accounts']['amount'] = $total;
            $depreciation[$d->getAccount()->getID()]['accounts']['space'] = "";

            $operating_activities_total += $total;
        } 

        $inv_activities = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_IA]);
        foreach ($inv_activities as $ia) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$ia->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$ia->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$ia->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$ia->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $investing_activities[$ia->getAccount()->getID()]['accounts']['increase']['code'] = $ia->getAccount()->getCode();
            $investing_activities[$ia->getAccount()->getID()]['accounts']['increase']['name'] = $ia->getAccount()->getName();
            $investing_activities[$ia->getAccount()->getID()]['accounts']['decrease']['code'] = $ia->getAccount()->getCode();
            $investing_activities[$ia->getAccount()->getID()]['accounts']['decrease']['name'] = $ia->getAccount()->getName();

            $investing_activities[$ia->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $investing_activities[$ia->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;
            $investing_activities[$ia->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $investing_activities[$ia->getAccount()->getID()]['accounts']['decrease']['space'] = "";
            $investing_activities_total += $total;
        } 

        $fin_activities = $em->getRepository('GistAccountingBundle:CashFlowSettings')->findBy(['type' => CashFlowSettings::TYPE_FA]);
        foreach ($fin_activities as $fa) {
            $debit = 0;
            $credit = 0;
            if(isset($coa_array[$fa->getAccount()->getID()]['total_debit']))
                $debit = $coa_array[$fa->getAccount()->getID()]['total_debit'];
            if(isset($coa_array[$fa->getAccount()->getID()]['total_credit']))
                $credit = -$coa_array[$fa->getAccount()->getID()]['total_credit'];
            $total = $debit + $credit;

            $financing_activities[$fa->getAccount()->getID()]['accounts']['increase']['code'] = $fa->getAccount()->getCode();
            $financing_activities[$fa->getAccount()->getID()]['accounts']['increase']['name'] = 'Add: Proceeds from '.$fa->getAccount()->getName();
            $financing_activities[$fa->getAccount()->getID()]['accounts']['decrease']['code'] = $fa->getAccount()->getCode();
            $financing_activities[$fa->getAccount()->getID()]['accounts']['decrease']['name'] = 'Deduct: Payment of '.$fa->getAccount()->getName();

            $financing_activities[$fa->getAccount()->getID()]['accounts']['increase']['amount'] = $debit;
            $financing_activities[$fa->getAccount()->getID()]['accounts']['decrease']['amount'] = $credit;
            $financing_activities[$fa->getAccount()->getID()]['accounts']['increase']['space'] = "";
            $financing_activities[$fa->getAccount()->getID()]['accounts']['decrease']['space'] = "";

            $financing_activities_total += $total;
        } 

        $list['accounts_receivable'] = $accounts_receivable;
        $list['prepaid_expenses'] = $prepaid_expenses;
        $list['accounts_payable'] = $accounts_payable;
        $list['depreciation'] = $depreciation;
        $list['investing_activities'] = $investing_activities;
        $list['financing_activities'] = $financing_activities;
        $list['disposal_assets'] = $disposal_assets;
        $list['interest_income'] = $interest_income;
        $list['interest_and_bank'] = $interest_and_bank;

        // Totals
        $list['operating_activities_total'] = $operating_activities_total;
        $list['investing_activities_total'] = $investing_activities_total;
        $list['financing_activities_total'] = $financing_activities_total;

        $list['net_total'] = ($operating_activities_total + $investing_activities_total + $financing_activities_total);

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

    public function generateTableAction($month, $year)
    {
        $data = $this->getRequest()->request->all();
        $cf = $this->getCashFlowData($month, $year);
        
        $date = new Datetime($year.'-'.$month.'-01 00:00:00');
        $date = $date->format(' F Y');

        $array['cf'] = $cf;
        $array['date'] = $date;

        return new JsonResponse($array);
    }
}