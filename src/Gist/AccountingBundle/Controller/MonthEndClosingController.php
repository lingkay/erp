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
use Gist\AccountingBundle\Entity\MonthEndClosing;
use Gist\AccountingBundle\Entity\EndingBalance;
use DateTime;
use SplFileObject;
use LimitIterator;

class MonthEndClosingController extends BaseController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'gist_month_end';
        $this->title = 'Month End Closing';
        $this->list_title = 'Month End Closing';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:MonthEndClosing";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $params = $this->getViewParams('List');

        $twig_file = 'GistAccountingBundle:MonthEndClosing:index.html.twig';

        $params['list_title'] = $this->list_title;
        // $params['grid_cols'] = $gl->getColumns();
        $this->padListParams($params);
        return $this->render($twig_file, $params);
    }

    protected function newBaseClass()
    {
        return new MonthEndClosing();
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
            // $grid->newJoin('a', 'chart_of_account', 'getAccount'),
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

   
    
    public function saveAction()
    {
        try
        {
            $em = $this->getDoctrine()->getManager();
            $am = $this->get('gist_accounting');
            $data = $this->getRequest()->request->all();
          
            // check the last month end
            $last_end = $em->getRepository('GistAccountingBundle:MonthEndClosing')
                           ->createQueryBuilder('o')
                           ->orderBy('o.id','DESC')
                           ->getQuery()->getOneOrNullResult();


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

    protected function saveEndingClosing($month_end, $data)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');
         
        //query all the entries in the TrialBalance Entity within the month end closed
        $from = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        $from_2 = new Datetime($data['year'].'-'.$data['month'].'-01 00:00:00');
        $last_day = $from_2->modify('last day of this month');
        $last_day = $last_day->format('d');
        $to   = new Datetime($data['year'].'-'.$data['month'].'-'.$last_day.' 23:59:59');

        //get COA balance per month
        $qb = $em->createQueryBuilder();
        $qb->select(['c.id as coa_id',
                     'sum(o.debit) as total_debit',
                     'sum(o.credit) as total_credit',
                    ])
            ->from('GistAccountingBundle:TrialBalance', 'o')
            ->join('GistAccountingBundle:ChartOfAccount', 'c', 'WITH', 'o.chart_of_account = c.id')
            ->where('o.date_create between :date_from and :date_to ')
            ->setParameter('date_from', $from)
            ->setParameter('date_to', $to)
            ->groupBy('c.id');

        $coa = $qb->getQuery()->getResult();

        // reloop to make key as id
        $coa_list = [];
        foreach ($coa as $c) {
            $coa_list[$c['coa_id']] = $c;
        }

        // get all the coa accounts 
        $coa_all = $em->getRepository('GistAccountingBundle:ChartOfAccount')->findAll();
        
        $coa_final = [];
        foreach ($coa_all as $c) {
            if(isset($coa_list[$c->getID()])) {
                $debit = $coa_list[$c->getID()]['total_debit'];
                $credit = $coa_list[$c->getID()]['total_credit'];
            }else{
                $debit = 0.00;
                $credit = 0.00;
            }
            $coa_final[$c->getID()]['id'] = $c->getID();
            $coa_final[$c->getID()]['debit'] = $debit;
            $coa_final[$c->getID()]['credit'] = $credit;
        }        

        // get the last month end Ending Balance and make it the beginning of the new month end
        // check the last month end
        $last_end = $em->getRepository('GistAccountingBundle:MonthEndClosing')
                       ->createQueryBuilder('o')
                       ->orderBy('o.id','DESC')
                       ->getQuery()->getOneOrNullResult();

        if($last_end == null) {
            // add debit + credit to compute the ending
            foreach ($coa_final as $c) {
                $ending = $c['debit'] + $c['credit'];
                $chart_of_account =  $am->findChartOfAccount($c['id']);
                $ending_balance = new EndingBalance();
                $ending_balance->setAccount($chart_of_account)
                               ->setMonthEnd($month_end)
                               ->setDebit($c['debit'])
                               ->setCredit($c['credit'])
                               ->setEnding($ending)
                               ->setMonth($data['month'])
                               ->setYear($data['year']);
                
                $em->persist($ending_balance);
            }
        }else{
            // add the ending of the $last_end + debit + credit to compute the ending
            foreach ($coa_final as $c) {
                $last_ending = $em->getRepository('GistAccountingBundle:EndingBalance')
                       ->createQueryBuilder('o')
                       ->where('o.month_end_closing = :MONTH_END')
                       ->andWhere('o.chart_of_account = :COA')
                       ->setParameter(':MONTH_END', $last_end->getID())
                       ->setParameter(':COA', $c['id'])
                       ->getQuery()->getOneOrNullResult();

                if($last_ending == null ) {
                    $e = 0.00;
                }else{
                    $e = $last_ending->getEnding();
                }

                $ending = $e + $c['debit'] + $c['credit'];
                $chart_of_account =  $am->findChartOfAccount($c['id']);
                $ending_balance = new EndingBalance();
                $ending_balance->setAccount($chart_of_account)
                               ->setMonthEnd($month_end)
                               ->setBeginning($e)
                               ->setDebit($c['debit'])
                               ->setCredit($c['credit'])
                               ->setEnding($ending)
                               ->setMonth($data['month'])
                               ->setYear($data['year']);
                
                $em->persist($ending_balance);
            }
        }

    }
}