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
use DateTime;
use SplFileObject;
use LimitIterator;

class CDJController extends CrudController
{
    use TrackCreate;

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

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('a', 'team', 'getTeam'),
    //         // $grid->newJoin('g', 'group', 'getGroup'),
    //     );
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Account Name', 'getName', 'name'),
            $grid->newColumn('Date', 'getRecordDate', 'record_date', 'o', [$this,'formatDate']),
            $grid->newColumn('Particulars', 'getNotes', 'notes'),
     
            $grid->newColumn('Debit', 'getDebit', 'debit'),
            $grid->newColumn('Credit', 'getCredit', 'credit'),
        );
    }

    // protected function padFormParams(&$params, $user = null)
    // {
	    
    //     $sm = $this->get('hris_settings');
    //     $um = $this->get('gist_user');

    //     $params['deposit_opts'] = $sm->getDepositOptions();
       
    //     $params['type_opts'] = [EmployeeDeposit::TYPE_RETURN => EmployeeDeposit::TYPE_RETURN,
    //     						EmployeeDeposit::TYPE_DEDUCTION => EmployeeDeposit::TYPE_DEDUCTION];
    //     $params['emp_opts'] = $um->getUserFullNameOptions();
    //     $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    // }
    // 
    

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
        }
        // $o->setName($data['name'])
        //     ->setCode($data['code'])
        //     ->setNotes($data['notes']);

        // $this->updateTrackCreate($o, $data, $is_new);
    }




}