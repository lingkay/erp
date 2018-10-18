<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\AccountingBundle\Entity\CDJJournalEntry;
use Gist\AccountingBundle\Entity\CDJTransaction;
use DateTime;
use SplFileObject;
use LimitIterator;

class CDJController extends CrudController
{
    use TrackCreate;

    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_cdj';
        $this->title = 'CDJ';
        $this->list_title = 'CDJ';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:CDJJournalEntry";
    }


    protected function newBaseClass()
    {
        return new CDJJournalEntry();
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
            $grid->newJoin('t', 'transaction', 'getTransaction'),
            $grid->newJoin('u', 'user_create', 'getUserCreate'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Record Date', 'getRecordDate', 'record_date', 'o', [$this,'formatDate']),
            $grid->newColumn('Transaction Code', 'getCode', 'code', 't'),
            $grid->newColumn('Account Name', 'getNameCode', 'name', 'a'),
            $grid->newColumn('Particulars', 'getNotes', 'notes'),
            $grid->newColumn('Debit', 'getDebit', 'debit', 'o', [$this,'formatPrice']),
            $grid->newColumn('Credit', 'getCredit', 'credit',  'o', [$this,'formatPrice']),
            $grid->newColumn('Prepared By', 'getDisplayName', 'user_create',  'u'),
       
        );
    }

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        // $obj = $this->newBaseClass();


        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        // $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, null);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }


    public function addSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');
        $data = $this->getRequest()->request->all();
        $this->hookPreAction();
        try
        {
            $this->update($data);
            $this->addFlash('success','CDJ Entries added successfully.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error',$e->getMessage());
         
            $this->addFlash('error', 'Database error occurred. Possible duplicate.');
         
            error_log($e->getMessage());
            return $this->addError(null);
        }
        catch (DBALException $e)
        {
             $this->addFlash('error',$e->getMessage());
         
            $this->addFlash('error', 'Database error occurred. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError(null);
        }
        catch (\Exception $e) {
            $this->addFlash('error',$e->getMessage());
            return $this->addError(null);

        }
    }

    protected function addError($obj = null)
    {
        $params = $this->getViewParams('Add');
        // $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        $this->padFormParams($params, null);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    protected function padFormParams(&$params, $obj = null)
    {
        $am = $this->get('gist_accounting');
        $params['account_opts'] = $am->getChartOfAccountOptions();
        $params['cdate'] = new DateTime();
    }
    
    protected function update($data)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');

        $record_date = new DateTime($data['record_date']);
        $transaction = new CDJTransaction($record_date);
        $transaction->setUserCreate($this->getUser());

        $em->persist($transaction);
        $em->flush();
        $transaction->setCDJCode($record_date);
        $transaction->setRecordDate($record_date);
        $transaction->setNotes($data['particulars']);
        $em->persist($transaction);

        foreach ($data['account'] as $key => $account_id) {
            $cdj_entry = new CDJJournalEntry();
            $account = $am->findChartOfAccount($account_id);
            $cdj_entry->setAccount($account)
                ->setDebit($data['debit'][$key])
                ->setCredit($data['credit'][$key])
                ->setNotes($data['particulars'])
                ->setUserCreate($this->getUser())
                ->setRecordDate($record_date)
                ->setTransaction($transaction);

            $em->persist($cdj_entry);
            $em->flush();

            $am->addTrialBalance($cdj_entry);
        }
    }

     protected function padListParams(&$params, $obj = null)
    {
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        return $params;

    }

    protected function hookPreAction()
    {
        $this->getControllerBase();
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




}