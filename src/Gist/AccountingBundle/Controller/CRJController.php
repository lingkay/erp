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
use Gist\AccountingBundle\Entity\CRJJournalEntry;
use DateTime;
use SplFileObject;
use LimitIterator;

class CRJController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_crj';
        $this->title = 'CRJ';
        $this->list_title = 'CRJ';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:CRJJournalEntry";
    }


    protected function newBaseClass()
    {
        return new CRJJournalEntry();
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
            $this->addFlash('success', $this->title . ' added successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
            }
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

    protected function padFormParams(&$params, $obj = null)
    {
        $am = $this->get('gist_accounting');
        $params['account_opts'] = $am->getChartOfAccountOptions();
    }

    protected function update($data)
    {
        $em = $this->getDoctrine()->getManager();


        // $o->setName($data['name'])
        //     ->setCode($data['code'])
        //     ->setNotes($data['notes']);

        // $this->updateTrackCreate($o, $data, $is_new);
    }


    public function settingsFormAction()
    {
        $this->checkAccess('gist_accounting_settings.view');
        $this->hookPreAction();
         
        $params = $this->getViewParams('Add');

        $twig_file = 'GistAccountingBundle:CRJ:settings.html.twig';
        
        $params['list_title'] = $this->list_title;
        $this->padFormParams($params);
        return $this->render($twig_file, $params);
 
    }

    public function settingsSubmitAction()
    {
        $this->checkAccess('gist_accounting_settings.view');
        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();

        $conf = $this->get('gist_configuration');

        $data = $this->getRequest()->request->all();

        $crj = ['sales_debit' => $data['sales_debit'],
                'receivable_credit' => $data['receivable_credit']];

        $conf->set('crj_settings', json_encode($crj));
        $em->flush();        
        $this->addFlash('success', 'CRJ Settings edited successfully.');

        return $this->redirect($this->generateUrl('gist_crj_settings_index'));

 
    }



}