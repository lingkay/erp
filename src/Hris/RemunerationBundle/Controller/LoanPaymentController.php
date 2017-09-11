<?php

namespace Hris\RemunerationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\RemunerationBundle\Entity\LoanPayment;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use DateTime;

class LoanPaymentController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
        $this->filter_url = 'hris_remuneration_loan_filter';
		$this->route_prefix = 'hris_remuneration_loan';
		$this->title = 'Payment';

		$this->list_title = 'Payment';
		$this->list_type = 'dynamic';

	}

    public function update($o,$data,$is_new = false){
     
        $em = $this->getDoctrine()->getManager();
        $cm = $this->get('hris_cashflow');
        
        $this->updateTrackCreate($o,$data,$is_new);
        $date_paid = new DateTime($data['date_paid']);
    
        $o->setDatePaid($date_paid);
        // $o->setCode($data['code']);
        $o->setPayment($data['amount']);
        $o->setLoan($cm->getLoan($data['loan']));

        $loan = $cm->getLoan($data['loan']);

        $current_bal = $loan->getBalance();
        $new_bal = $current_bal - $data['amount'];
        if ($new_bal == 0) {
            $loan->setStatus('Paid');
        }
        $loan->setBalance($new_bal);

        $current_pay = $loan->getPaid();
        $new_pay = $current_pay + $data['amount'];
        $loan->setPaid($new_pay);

        $em->persist($loan);
        $em->flush();
    }

    protected function hookPostSave($obj, $is_new = false)
    {

        // if($is_new){
        //     $config = $this->get('gist_configuration');
        //     $settings = $this->get('hris_settings');
        //     $hr = $settings->getDepartment($config->get('hris_hr_department'));

            $em = $this->getDoctrine()->getManager();
            $cm = $this->get('hris_cashflow');
            $obj->setCode($cm->generateLoanPaymentCode($obj));
            $em->persist($obj);
            $em->flush();


        //     $event = new NotificationEvent();
        //     $event->notify(array(
        //         'source'=> 'New Reimbursement',
        //         'link'=> $this->generateUrl('hris_remuneration_loan_edit_form',array('id'=>$obj->getID())),
        //         'message'=> $obj->getEmployee()->getDisplayName().' sent a loan request.',
        //         'type'=> Notification::TYPE_UPDATE,
        //         'receipient' => $hr));

        //         $dispatcher = $this->get('event_dispatcher');
        //         $dispatcher->dispatch('notification.event', $event);
        // }

       }

    public function addAction($id)
    {
        $this->checkAccess($this->route_prefix . '.add');
        $cm = $this->get('hris_cashflow');
        $this->hookPreAction();
        $obj = $this->newBaseClass();
        $obj->setLoan($cm->getLoan($id));

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('GistTemplateBundle:Object:add.html.twig', $params);
    }

    public function addSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        try
        {
            $obj = $this->newBaseClass();
            $this->add($obj);

            $this->addFlash('success', $this->title . ' added successfully.');
            return $this->redirect($this->generateUrl('hris_remuneration_loan_edit_form',array('id'=>$obj->getLoan()->getID())));
    
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
              $this->addFlash('error', $e->getMessage());
            
          //  $this->addFlash('error', 'Database error encountered. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }
    }



	protected function getObjectLabel($obj) 
    {
        if ($obj == null)
            return '';
        return $obj->getCode();
    }

    protected function newBaseClass() {
            return new LoanPayment();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array (
            $grid->newJoin('emp','employee','getEmployee','left'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Code', 'getCode', 'code'),
            $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed','o',array($this,'formatDate')),
            $grid->newColumn('Date Approved', 'getDateApproved', 'date_approved','o',array($this,'formatDate')),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'emp'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Status', 'getDisplayStatus', 'status'),
            $grid->newColumn('Cost(Php)', 'getCost', 'cost','o',array($this,'formatPrice')),
        );
    }

    public function gridReimbursementAction($id = null, $date_from = null, $date_to = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterReimbursementGrid($id,$date_from,$date_to));
        $gres = $gloader->load();  
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        //if loan is new
        if($object->getUserCreate() == null)
        {
            $params['new'] = true;
        }else {
            $params['new'] = false;
        }

        
        //return $params;
    }

}