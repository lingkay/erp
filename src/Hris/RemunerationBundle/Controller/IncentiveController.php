<?php

namespace Hris\RemunerationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\RemunerationBundle\Entity\Incentive;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use DateTime;

class IncentiveController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
        $this->filter_url = 'hris_remuneration_incentive_filter';
		$this->route_prefix = 'hris_remuneration_incentive';
		$this->title = 'Incentive';

		$this->list_title = 'Incentives';
		$this->list_type = 'dynamic';
	}

    // public function indexAction()
    // {
    //     $this->hookPreAction();

    //     $gl = $this->setupGridLoader();

    //     $params = $this->getViewParams('', 'hris_remuneration_incentive_index');

    //     $twig_file = 'HrisWorkforceBundle:Reimbursement:index.html.twig';

    //     $date_from = new DateTime();
    //     $date_to = new DateTime();
    //     $date_from->format("Y-m-d");
    //     $date_to->format("Y-m-d");

    //     $params['list_title'] = $this->list_title;
    //     $params['grid_cols'] = $gl->getColumns();

    //     return $this->render($twig_file, $params);
    // }

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
    
        if($data['type'] == 'Others')
        {
            $o->setType($data['other_type']);
    
        }else {
            $o->setType($data['type']);
        }

        $o->setCost($data['cost']);

    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'emp_name' => $o->getEmployee()->getDisplayName(),
            'emp_id' => $o->getEmployee()->getID(),
            'date_approved' => $o->getDateApprovedDisplay(),
            'type' => $o->getType(),
            'cost' => $o->getCost()
        );

        return $data;
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        if($is_new){
            $config = $this->get('gist_configuration');
            $settings = $this->get('hris_settings');
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            $em = $this->getDoctrine()->getManager();
            $cm = $this->get('hris_cashflow');
            $obj->setCode($cm->generateIncentiveCode($obj));
            $em->persist($obj);
            $em->flush();


            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'New Incentive',
                'link'=> $this->generateUrl('hris_remuneration_incentive_edit_form',array('id'=>$obj->getID())),
                'message'=> $obj->getEmployee()->getDisplayName().' sent a incentive request.',
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $hr));

                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
        }
    }

    public function statusUpdateAction(Incentive $reimburse, $status)
    {
        $em = $this->getDoctrine()->getManager();
        if($status == "Approved")
        {
            $date = new DateTime();
            $reimburse->setApprovedBy($this->getUser()->getEmployee());
            $reimburse->setDateApproved($date);  
        }
        $reimburse->setStatus($status);
        $em->flush();

        $this->addFlash('success', 'Incentive ' . $reimburse->getCode() . ' status has been updated to ' . $status . '.');

        return $this->redirect($this->generateUrl('hris_remuneration_incentive_index'));
    }

	protected function getObjectLabel($obj) 
    {
        if ($obj == null)
            return '';
        return $obj->getCode();
    }

    protected function newBaseClass() {
            return new Incentive();
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

        //if incentive is new
        if($object->getUserCreate() == null)
        {
            $params['new'] = true;
        }else {
            $params['new'] = false;
        }

        $type_opts = array(
            'Marketing' => 'Marketing',
            'Operation' => 'Operation',
            'Sales' => 'Sales',
            'Productivity' => 'Productivity',
            'Others' => 'Others'
            );
        $params['type_opts'] = $type_opts;
        
        if ($object !== NULL) {
            if ($object->getStatus() == Incentive::STATUS_APPROVED) {
                $params['readonly'] = true;
            }
        }
        
        //return $params;
    }

}