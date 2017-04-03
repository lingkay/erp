<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\WorkforceBundle\Entity\Reimbursement;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use DateTime;

class ReimbursementController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
        $this->filter_url = 'hris_workforce_reimbursement_filter';
		$this->route_prefix = 'hris_workforce_reimbursement';
		$this->title = 'Reimbursement';

		$this->list_title = 'Reimbursement';
		$this->list_type = 'dynamic';
	}

    public function indexAction()
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('', 'hris_workforce_reimbursement_index');

        $twig_file = 'HrisWorkforceBundle:Reimbursement:index.html.twig';

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    public function update($o,$data,$is_new = false){
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $em = $this->getDoctrine()->getManager();
        $media = $this->get('gist_media');

        $this->updateTrackCreate($o,$data,$is_new);
        $date_filed = new DateTime($data['date_filed']);
        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['name_id']);

        $o->setEmployee($employee);
        $o->setDateFiled($date_filed);
        $o->setCode($data['code']);
        if($data['picture']!=0 && $data['picture'] != ""){
            $o->setUpload($media->getUpload($data['picture']));
        }

        if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
            $o->setUpload($media->getUpload($data['file']));
        }

        if($data['expense_type'] == 'Others')
        {
            $o->setExpense($data['other_type']);
            $o->setReceipt($data['receipt_no']);
        }
        elseif($data['expense_type'] == 'Transportation')
        {
            $o->setExpense($data['expense_type']);
            $o->setReceipt($data['serial_no']);
        }
        else {
            $o->setExpense($data['expense_type']);
            $o->setReceipt($data['receipt_no']);
        }

        $o->setCost($data['cost']);

    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'emp_name' => $o->getEmployee()->getDisplayName(),
            'emp_id' => $o->getEmployee()->getID(),
            'expense_type' => $o->getExpense(),
            'upload' => $o->getUpload(),
            'receipt_no' => $o->getReceipt(),
            'cost' => $o->getCost()
        );

        return $data;
    }

    protected function notifyApprover($receipient, $reimburse_id,$message,$source)
    {
        $settings = $this->get('hris_settings');

        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> $source,
            'link'=> $this->generateUrl('hris_workforce_reimbursement_edit_form',array('id'=>$reimburse_id)),
            'message'=> $message,
            'type'=> Notification::TYPE_UPDATE,
            'receipient' => $receipient));

        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');

        if($is_new){
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            $em = $this->getDoctrine()->getManager();
            $cm = $this->get('hris_cashflow');
            $obj->setCode($cm->generateReimbursementCode($obj));
            $em->persist($obj);
            $em->flush();

            $message = 'A new Reimbursement request needs to be reviewed.';
            $source = 'New Reimbursement';

            $reviewers = $settings->getEmployeesByJobTitle($config->get('hris_hr_compen_ben'));

            if (!empty($reviewers)) {
                foreach ($reviewers as $reviewer) {
                    $this->notifyApprover($reviewer, $obj->getID(), $message, $source);
                }
            } else {
                $this->notifyApprover($hr, $obj->getID(), $message);
            }
        }
        if ($obj->getStatus() !== 'Pending') {
            if ($obj->getStatus() == 'Reviewed') {
                $message = 'A Reimbursement request needs your approval.';
                $source = 'Reimbursement for Approval';

                $compen = $settings->getEmployeesByJobTitle($config->get('hris_hr_compen_ben'));
                $vp = $settings->getEmployeesByJobTitle($config->get('hris_vp_operations'));
                $hr = $settings->getDepartment($config->get('hris_hr_department'));

                if (!empty($vp)) {
                    foreach ($vp as $veep) {
                        $this->notifyApprover($veep,$obj->getID(),$message,$source);
                    }
                } elseif (!empty($compen)) {
                    foreach ($compen as $comp) {
                        $this->notifyApprover($comp,$obj->getID(),$message,$source);
                    }
                } else {
                    $this->notifyApprover($hr, $obj->getID(), $message);
                }
            } elseif ($obj->getStatus() == 'Approved') {
                $message = 'Your reimbursement request has been approved.';
                $source = 'Reimbursement Request Approved';
                $this->notifyApprover($obj->getEmployee(), $obj->getID(), $message, $source);
            } elseif ($obj->getStatus() == 'Reject') {
                $message = 'Your reimbursement request has been rejected.';
                $source = 'Reimbursement Request Rejected';
                $this->notifyApprover($obj->getEmployee(), $obj->getID(), $message, $source);
            }
        }
    }

    public function statusUpdateAction(Reimbursement $reimburse, $status)
    {
        $em = $this->getDoctrine()->getManager();
        if($status == "Approved") {
            $date = new DateTime();
            $reimburse->setApprovedBy($this->getUser());
            $reimburse->setDateApproved($date);  
        }

        $reimburse->setStatus($status);

        if($reimburse->getRequest() != null)
        {
            $reimburse->getRequest()->setStatus($status);    
        }

        $em->flush();
        $this->hookPostSave($reimburse);

        // if ($reimburse->getStatus() == Reimbursement::STATUS_APPROVED)
        // {
        //     $form_url = $this->generateUrl('hris_workforce_reimbursement_edit_form',array('id'=>$reimburse->getID()));
            

        //     $event = new NotificationEvent();
        //         $event->notify(array(
        //             'source'=> 'Reimbursement approved',
        //             'link'=> $form_url,
        //             'message'=> 'Your Reimbursement has been approved',
        //             'type'=> Notification::TYPE_UPDATE,
        //             'receipient' => $reimburse->getEmployee()));

        //     $dispatcher = $this->get('event_dispatcher');
        //     $dispatcher->dispatch('notification.event', $event);
        // }

        $this->addFlash('success', 'Reimbursement ' . $reimburse->getCode() . ' status has been updated to ' . $status . '.');
        return $this->redirect($this->generateUrl('hris_workforce_reimbursement_index'));
    }

	protected function getObjectLabel($obj) 
    {
        if ($obj == null)
            return '';
        return $obj->getCode();
    }

    protected function newBaseClass() {
            return new Reimbursement();
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
            $grid->newColumn('Expense Type', 'getExpense', 'expense_type'),
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

    protected function filterReimbursementGrid($id = null, $date_from = null, $date_to = null){
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $date = new DateTime();

        $date_from = $date_from=='null'? new DateTime($date->format('Ym01')):new DateTime($date_from);
        $date_to = $date_to=='null'? new DateTime($date->format('Ymt')):new DateTime($date_to);

        $qry[] = "(o.date_filed >= '".$date_from->format('Y-m-d')."' AND o.date_filed <= '".$date_to->format('Y-m-d')."')";
        if($id != null and $id != 'null')
        {
            $qry[] = "(o.employee = '".$id."')";    
        }
        
        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        //if reimbursement is new
        if ($object != null) 
        {
            if($object->getUserCreate() == null)
            {
                $params['new'] = true;
            }else {
                $params['new'] = false;
            }
        }
        else
        {
           $params['new'] = 'archived';
        }

        $expense_opts = array(
            'Meal' => 'Meal Allowance',
            'Travel' => 'Travel Allowance',
            'Transportation' => 'Transportation Expense',
            'Others' => 'Others'
            );
        $params['expense_opts'] = $expense_opts;
        
        if ($object !== NULL) {
            if ($object->getStatus() !== Reimbursement::STATUS_PENDING) {
                $params['readonly'] = true;
            }
        }
        
        //return $params;
    }

}