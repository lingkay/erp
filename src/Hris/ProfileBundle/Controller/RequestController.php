<?php

namespace Hris\ProfileBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use Hris\WorkforceBundle\Entity\Reimbursement;
use Hris\WorkforceBundle\Entity\Resign;
use Hris\WorkforceBundle\Entity\Request;
use Hris\WorkforceBundle\Entity\Employee;
use Gist\UserBundle\Entity\User;
use Hris\AdminBundle\Entity\Leave\LeaveType;

use DateTime;

class RequestController extends CrudController
{
	use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_profile_request';
		$this->title = 'Request';

		$this->list_title = 'Request';
		$this->list_type = 'dynamic';
		$this->repo = 'HrisWorkforceBundle:Request';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render('HrisProfileBundle:Request:index.html.twig', $params);
    }

    public function createFormAction($type)
    {
        $this->checkAccess($this->route_prefix . '.add');
        $this->hookPreAction();
        // $obj = $this->newBaseClass();

        $params = $this->getViewParams('Add');
        

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        if ($type == 'reimburse') {
            $obj = new Reimbursement();
            $req = new Request();
            $params['object'] = $obj;
            $params['twig_file'] = 'HrisProfileBundle:Request:reimburse.html.twig';
            $this->reimburseFormParams($params, $obj);
        }
        elseif ($type == 'coe') {
            $obj = new Request();
            $params['object'] = $obj;
            $params['twig_file'] = 'HrisProfileBundle:Request:coe.html.twig';
            $this->padFormParams($params, $obj);
        }
        elseif ($type == 'resign') {
            $obj = new Resign();
            $params['object'] = $obj;
            $params['twig_file'] = 'HrisProfileBundle:Request:resign.html.twig';
            $this->padFormParams($params, $obj);
        }

        return $this->render('HrisProfileBundle:Request:add.html.twig', $params);
    }

    public function viewFormAction($id, $type)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

         if ($type == 'reimburse') {
            $params['type'] = Request::TYPE_REIMBURSE;
            $params['object'] = $em->getRepository('HrisWorkforceBundle:Reimbursement')->findOneBy(
                    array('request' => $obj->getID())
                );
            $params['twig_file'] = 'HrisProfileBundle:Request:reimburse.html.twig';
            $this->reimburseFormParams($params, $obj);
        }
        elseif ($type == 'coe') {
            $params['type'] = Request::TYPE_COE;
            //will redirect to index when reuqest has been deleted already
            if ($obj == null) 
            {
                $this->hookPreAction();

                $gl = $this->setupGridLoader();

                $params = $this->getViewParams('List');

                $params['list_title'] = $this->list_title;
                $params['grid_cols'] = $gl->getColumns();
                $params['twig_file'] = 'HrisProfileBundle:Request:index.html.twig';
            }
            else
            {
                $params['twig_file'] = 'HrisProfileBundle:Request:coe.html.twig';
                $params['notes'] = unserialize($obj->getNotes());
                // $params['readonly'] = ($obj->getStatus) ? a : b ;;
                $this->padFormParams($params, $obj);
            }
        }
        elseif ($type == 'resign') {
            $params['type'] = Request::TYPE_RESIGN;
            $params['object'] = $em->getRepository('HrisWorkforceBundle:Resign')->findOneBy(
                    array('request' => $obj->getID())
                );
            $params['twig_file'] = 'HrisProfileBundle:Request:resign.html.twig';
            $this->padFormParams($params, $obj);
        }

        return $this->render('HrisProfileBundle:Request:edit.html.twig', $params);
    }

    public function callbackGrid($id)
    {
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

        switch ($obj->getRequestType()) {
            case Request::TYPE_REIMBURSE:
                $type = 'reimburse';
                break;
            case Request::TYPE_COE:
                $type = 'coe';
                break;
            case Request::TYPE_PROP:
                $type = 'property';
                break;
            case Request::TYPE_RESIGN:
                $type = 'resign';;
                break;
        }

        $params = array(
            'id' => $id,
            'type' => $type,
            'status' => $obj->getStatus(),
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisProfileBundle:Request:action.html.twig',
            $params
        );
    }

	protected function getObjectLabel($obj) {
        if ($obj == null)
            return '';
        return $obj->getID();
    }

    protected function newBaseClass() {
        return new Request();
    }

    protected function reimburseFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        //if reimbursement is new
        if($object->getUserCreate() == null)
        {
            $params['new'] = true;
        }else {
            $params['new'] = false;
        }

        $expense_opts = array(
            'Meal' => 'Meal Allowance',
            'Travel' => 'Travel Allowance',
            'Transportation' => 'Transportation Expense',
            'Others' => 'Others'
            );
        $params['expense_opts'] = $expense_opts;
        
        if ($object !== NULL) {
            if ($object->getStatus() == Reimbursement::STATUS_APPROVED) {
                $params['readonly'] = true;
            }
        }
        
        return $params;
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $coe_opts = array(
            'Compensation' => 'Compensation',
            'Job' => 'Job Description',
            'Requirement' => 'Local Requirment',
            'Others' => 'Others'
            );
        $params['coe_opts'] = $coe_opts;
        
        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {

    	// echo "<pre>";
    	// print_r($data);
    	// echo "</pre>";
    	// die();

        if ($is_new) 
        {
            $o->setStatus(Request::STATUS_PENDING);
            
        }

    	$em = $this->getDoctrine()->getManager();

        if ($data['emp_id'] != NULL) {
            $emp = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
            $o->setEmployee($emp);
        } else {
            $o->setEmployee($this->getUser()->getEmployee());   
        }
        
        $o->setDateFiled(new DateTime());

        switch($data['action_btn']){
            case 'reimburse':
                $o->setRequestType(Request::TYPE_REIMBURSE);
                $em->persist($o);
                $obj = new Reimbursement();
                $this->updateReimburse($obj, $o, $data, $is_new);
                break;
            case 'coe':
                $o->setRequestType(Request::TYPE_COE);
                $this->updateCoe($o, $data, $is_new);
                break;
            case 'resign':
                $o->setRequestType(Request::TYPE_RESIGN);
                $em->persist($o);
                $obj = new Resign();
                $this->updateResign($o,$obj,$data,$is_new);
                break;
        }

    	$this->updateTrackCreate($o, $data, $is_new);

    }

    public function updateStatusAction($id,$type,$status)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        $obj = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

        switch ($status) {
            case 'approve':
                $obj->setStatus(Request::STATUS_APPROVE);
                $obj->setDateApproved(new DateTime());
                $obj->setApprovedBy($this->getUser()->getName());
                break;
            case 'save':
                $obj->setStatus(Request::STATUS_PRINT);
                if ($type == 'coe') {
                    $obj->setNotes(serialize($data));
                }
                break;
            case 'edit':
                $obj->setStatus(Request::STATUS_APPROVE);
                $em->persist($obj);
                $em->flush();
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID(), 'type'=>$type)));
                break;
            case 'reject':
                $obj->setStatus(Request::STATUS_REJECT);
                break;
        }
        $em->persist($obj);
        $em->flush();

        if ($obj->getStatus() == Request::STATUS_APPROVE)
        {
            $form_url = "";
            if($obj->getRequestType() == Request::TYPE_REIMBURSE) 
            {
                $reimburse = $em->getRepository('HrisWorkforceBundle:Reimbursement')->findOneBy(array('request' => $obj->getID()));
                $form_url = $this->generateUrl('hris_workforce_reimbursement_edit_form',array('id'=>$reimburse->getID()));
            }
            elseif ($obj->getRequestType() == Request::TYPE_COE) 
            {
                $form_url = $this->generateUrl('hris_profile_request_edit_form',array('id'=>$obj->getID(), 'type'=>'coe'));
            }
            elseif($obj->getRequestType() == Request::TYPE_RESIGN) 
            {
                $form_url = $this->generateUrl('hris_workforce_resign_edit_form',array('id'=>$obj->getID()));
            }

            $event = new NotificationEvent();
                $event->notify(array(
                    'source'=> $obj->getRequestType().' request approved',
                    'link'=> $form_url,
                    'message'=> 'Your '.$obj->getRequestType().' request has been approved',
                    'type'=> Notification::TYPE_UPDATE,
                    'receipient' => $obj->getEmployee()));

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }

        $this->addFlash('success', $this->title . ' for ' . $obj->getRequestType() . ' status updated successfully.');
        return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID(), 'type'=>$type)));
    }

    public function viewSubmitAction($id,$type)
    {
        $em = $this->getDoctrine()->getManager();

        $obj = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

        $data = $this->getRequest()->request->all();

        switch ($type) {
            case 'coe':
                $obj->setNotes($data['reason']);
                break;
            case 'reimburse':
                $media = $this->get('gist_media');
                $reimburse = $em->getRepository('HrisWorkforceBundle:Reimbursement')->findOneBy(array('request'=>$obj->getID()));

                if($data['expense_type'] == 'Others')
                {
                    $reimburse->setExpense($data['other_type']);
                    $reimburse->setReceipt($data['receipt_no']);
                    if($data['picture']!=0 && $data['picture'] != ""){
                    $reimburse->setUpload($media->getUpload($data['picture']));
                    }
                }
                elseif($data['expense_type'] == 'Transportation')
                {
                    $reimburse->setExpense($data['expense_type']);
                    $reimburse->setReceipt($data['serial_no']);
                    if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
                    $reimburse->setUpload($media->getUpload($data['file']));
                    }
                }
                else {
                    $reimburse->setExpense($data['expense_type']);
                    $reimburse->setReceipt($data['receipt_no']);
                    if($data['picture']!=0 && $data['picture'] != ""){
                    $reimburse->setUpload($media->getUpload($data['picture']));
                    }
                }

                $reimburse->setCost($data['cost']);
                $em->persist($reimburse);
                $em->flush();
                break;
            case 'resign':
                $media = $this->get('gist_media');
                $resign = $em->getRepository('HrisWorkforceBundle:Resign')->findOneBy(array('request'=>$obj->getID()));
                if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
                    $resign->setUpload($media->getUpload($data['file']));
                    $em->persist($resign);
                    $em->flush();
                }
                break;
        }

        

        $em->persist($obj);
        $em->flush();

        $this->addFlash('success', $this->title . ' for ' . $obj->getRequestType() . ' request updated successfully.');
        return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID(), 'type'=>$type)));
    }

    public function updateCoe($obj,$data,$is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        $obj->setDateFiled(new DateTime($data['date_filed']));

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);
        $obj->setEmployee($employee);
        $obj->setStatus(Request::STATUS_PENDING);

        $obj->setNotes(serialize($data));
    }

    public function updateReimburse($obj,$o,$data,$is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $media = $this->get('gist_media');

        $this->updateTrackCreate($obj,$data,$is_new);
        $date_filed = new DateTime($data['date_filed']);
        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['emp_id']);

        $obj->setEmployee($employee);
        $obj->setDateFiled($date_filed);
        $obj->setCode($data['code']);
        if($data['picture']!=0 && $data['picture'] != ""){
            $obj->setUpload($media->getUpload($data['picture']));
        }

        if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
            $obj->setUpload($media->getUpload($data['file']));
        }

        if($data['expense_type'] == 'Others')
        {
            $obj->setExpense($data['other_type']);
            $obj->setReceipt($data['receipt_no']);
        }
        elseif($data['expense_type'] == 'Transportation')
        {
            $obj->setExpense($data['expense_type']);
            $obj->setReceipt($data['serial_no']);
        }
        else {
            $obj->setExpense($data['expense_type']);
            $obj->setReceipt($data['receipt_no']);
        }

        $obj->setCost($data['cost']);
        $obj->setRequest($o);

        $em->persist($obj);
        $em->flush();
    }

    protected function updateResign($o,$obj, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $this->get('hris_settings');
        $media = $this->get('gist_media');

        $this->updateTrackCreate($obj,$data,$is_new);
        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['name_id']);

        $obj->setEmployee($employee);
        if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
            $obj->setUpload($media->getUpload($data['file']));
        }
        $obj->setRequest($o);
        $em->persist($obj);
        $em->flush();
    }

    public function requestSubmitAction($type)
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        try
        {
            $obj = $this->newBaseClass();
            $this->add($obj);

            $this->addFlash('success', $this->title . ' added successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
            }
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error encountered. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }
    }

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array (
    //         $grid->newJoin('l','leave_type','getLeaveType'),
    //     );
    // }

    protected function notifyApprover($receipient,$link,$message,$source)
    {
        $settings = $this->get('hris_settings');

        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> $source,
            'link'=> $link,
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
        $em = $this->getDoctrine()->getManager();
        $cm = $this->get('hris_cashflow');
        if($is_new)
        {
            if($obj->getRequestType() == Request::TYPE_REIMBURSE) 
            {
                $hr = $settings->getDepartment($config->get('hris_hr_department'));

                $em = $this->getDoctrine()->getManager();
                $cm = $this->get('hris_cashflow');
                $reimburse = $em->getRepository('HrisWorkforceBundle:Reimbursement')->findOneBy(array('request' => $obj->getID()));
                $reimburse->setCode($cm->generateReimbursementCode($reimburse));
                $em->persist($reimburse);
                $em->flush();

                $message = 'A new Reimbursement request needs to be reviewed.';
                $source = 'New Reimbursement';
                $link = $this->generateUrl('hris_workforce_reimbursement_edit_form',array('id'=>$reimburse->getID()));

                $reviewers = $settings->getEmployeesByJobTitle($config->get('hris_hr_compen_ben'));

                if (!empty($reviewers)) {
                    foreach ($reviewers as $reviewer) {
                        $this->notifyApprover($reviewer, $link, $message, $source);
                    }
                } else {
                    $this->notifyApprover($hr, $link, $message, $source);
                }
            }
            elseif ($obj->getRequestType() == Request::TYPE_COE) 
            {
                $hr = $settings->getDepartment($config->get('hris_hr_department'));

                $message = $obj->getEmployee()->getDisplayName().' requested for Certificate of Employment.';
                $source = 'New request for Certificate of Employment';
                $link = $this->generateUrl('hris_profile_request_edit_form',array('id'=>$obj->getID(), 'type'=>'coe'));

                $reviewers = $settings->getEmployeesByJobTitle($config->get('hris_hr_emp_rel'));

                if (!empty($reviewers)) {
                    foreach ($reviewers as $reviewer) {
                        $this->notifyApprover($reviewer, $link, $message, $source);
                    }
                } else {
                    $this->notifyApprover($hr, $link, $message, $source);
                }
            }
            elseif($obj->getRequestType() == Request::TYPE_RESIGN) 
            {
                $resign = $em->getRepository('HrisWorkforceBundle:Resign')->findOneBy(array('request' => $obj->getID()));
                $dept = $obj->getEmployee()->getDepartment();
                $dept_head = $dept->getDeptHead();
                $event = new NotificationEvent();
                $event->notify(array(
                    'source'=> 'Employee tendered his/her resignation',
                    'link'=> $this->generateUrl('hris_workforce_resign_edit_form',array('id'=>$resign->getID())),
                    'message'=> $obj->getEmployee()->getFirstName().' '.$obj->getEmployee()->getLastName().' tendered his/her resignation.',
                    'type'=> Notification::TYPE_UPDATE,
                    'receipient' => $dept_head));

                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
            }
        }
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array (
            $grid->newJoin('e','employee','getEmployee'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        if ($this->getUser()->getEmployee() == NULL) {
            return array(
                $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'e'),
                $grid->newColumn('Request Type', 'getRequestType', 'request_type'),
                $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed', 'o', array($this,'formatDate')),
                $grid->newColumn('Status','getStatus','status'),                        
            );
        }
        else {
            return array(
                $grid->newColumn('Request Type', 'getRequestType', 'request_type'),
                $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed', 'o', array($this,'formatDate')),
                $grid->newColumn('Status','getStatus','status'),                        
            );
        }
    }

    public function gridAction()
    {
        $gl = $this->setupGridLoader();
        $qry = array();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if ($this->getUser()->getEmployee() == NULL) {
            $gl->setQBFilterGroup($this->filterGrid());
        }
        else {
            $id = $this->getUser()->getEmployee()->getID();
            
            $qry[] = "(o.employee = '".$id."')";

            if (!empty($qry))
            {
                $filter = implode(' AND ', $qry);
                $fg->where($filter);
                $gl->setQBFilterGroup($fg);
            }
        }
        

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function deleteRequestAction($id,$type)
    {
        $this->checkAccess($this->route_prefix . '.delete');

        try
        {
            $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();

            $object = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

            switch ($type) {
                case 'coe':
                    $em->remove($object);
                    break;
                case 'reimburse':
                    $obj = $em->getRepository('HrisWorkforceBundle:Reimbursement')->findOneBy(
                                array('request' => $object->getID())
                            );
                    $em->remove($obj);
                    $em->flush();
                    $em->remove($object);
                    break;
                case 'resign':
                    $obj = $em->getRepository('HrisWorkforceBundle:Resign')->findOneBy(
                                array('request' => $object->getID())
                            );
                    $em->remove($obj);
                    $em->flush();
                    $em->remove($object);
                    break;
            }


            $odata = $object->toData();
            $this->logDelete($odata);

            // $em->remove($object);
            $em->flush();

            $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' has been deleted.');

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
        catch (DBALException $e)
        {
            // $this->addFlash('error', $e->getMessage());
            
            $this->addFlash('error', 'Could not delete ' . $this->title . '.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }

    public function printFormAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        // die();

        $obj = $em->getRepository('HrisWorkforceBundle:Request')->find($id);

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisReportBundle:Attendance:print.html.twig";

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
        if ($conf->get('hris_com_logo') != '') 
        {
            $path = $media->getUpload($conf->get('hris_com_logo'));

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            $str = ltrim($str, '/');

            $params['logo'] = $str;
        }
        else
        {
            $params['logo'] = '';
        }

        $this->hookPreAction();

        $emp = $obj->getEmployee();

        $params['emp'] = $emp;
        $params['position'] = $emp->getJobTitle()->getName();
        $params['date_hired'] = $emp->getDateHired();
        $params['date_end'] = 'Present';
        $params['date_approved'] = $obj->getDateApproved();
        $data = unserialize($obj->getNotes());
        $params['msg_body'] = $data['coe_body'];

        $config = $this  ->get('gist_configuration');
        if ($conf->get('hris_com_info_company_name') != null) 
        {
            $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        }

        if ($conf->get('hris_com_info_website') != null) 
        {
            $params['company_website'] = $conf->get('hris_com_info_website');
        }

        if ($conf->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $twig = 'HrisProfileBundle:Request:print.html.twig';

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('LETTER');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());

        // // debug
        // return $this->render($twig, $params);
    }
}