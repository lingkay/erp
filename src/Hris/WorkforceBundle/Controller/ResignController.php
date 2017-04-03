<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;

use Hris\WorkforceBundle\Entity\Resign;
use Hris\ProfileBundle\Entity\Request;
use Hris\WorkforceBundle\Entity\Employee;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use DateTime;

class ResignController extends CrudController
{
    use TrackCreate;

	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_resign';
		$this->title = 'Resignation';

		$this->list_title = 'Resignations';
		$this->list_type = 'dynamic';
        $this->repo = 'HrisWorkforceBundle:Resign';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $twig_file = 'HrisWorkforceBundle:Resign:index.html.twig';

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        //if reimbursement is new
        if ($object != null) 
        {
            $params['new'] = 'unarchived';
        }
        else
        {
           $params['new'] = 'archived';
        }
    }

    public function update($o,$data,$is_new = false){

        $em = $this->getDoctrine()->getManager();
        $setting = $this->get('hris_settings');
        $media = $this->get('gist_media');

        $this->updateTrackCreate($o,$data,$is_new);

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->find($data['name_id']);

        $o->setEmployee($employee);
        if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
            $o->setUpload($media->getUpload($data['file']));
        }

        switch($data['action_btn']) 
        {
            case 'accept':
                $date = new DateTime();
         
                $o->setApprovedBy($this->getUser());    
                $o->setDateApproved($date);
                $o->setStatus(Resign::STATUS_ACCEPT);
                break;
            case 'reject':
                $o->setApprovedBy($this->getUser());
                $o->setStatus(Resign::STATUS_REJECT);
                break;
        }
    }

    protected function notify($receipient,$link,$message,$source)
    {
        // $settings = $this->get('hris_settings');

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
        $settings = $this->get('hris_settings');
        $obj->getRequest()->setStatus($obj->getStatus());
        $config = $this->get('gist_configuration');
        $request = $obj->getRequest();

        if($obj->getStatus() == Resign::STATUS_ACCEPT)
        {
            $emp_id = $obj->getEmployee()->getID();

            $emp = $settings->getEmployee($emp_id);
            $emp->setEnabled(false);

            $user = $emp->getUser();
            $user->setEnabled(false);

            $source = 'Employee has resigned.';
            $message = $emp->getDisplayName().' had submitted his/her resignation and was approved by his/her respective supervisor.';

            // $compen = $settings->getEmployeesByJobTitle($config->get('hris_hr_compen_ben'));
            $vp = $settings->getEmployeesByJobTitle($config->get('hris_vp_operations'));
            $hr = $settings->getDepartment($config->get('hris_hr_department'));
            $link = $this->generateUrl('hris_workforce_resign_edit_form',array('id'=>$obj->getID()));

            if (!empty($vp)) {
                foreach ($vp as $veep) {
                    $this->notify($veep,$link,$message,$source);
                }
            }
            // } elseif (!empty($compen)) {
            //     foreach ($compen as $comp) {
            //         $this->notify($comp,$link,$message,$source);
            //     }
            // } else {
                $this->notify($hr, $link, $message, $source);
            // }
        }
        elseif ($obj->getStatus() == Resign::STATUS_REJECT)
        {
            if ($request != NULL) {
                $request->setStatus(Resign::STATUS_REJECT);
            }
            $source = 'Your resignation has been rejected.';
            $message = 'Your resignation has been rejected by the Administration. Please contact your HR Administrator for more information.';
            $link = $this->generateUrl('hris_profile_request_edit_form',array('id'=>$request->getID(), 'type'=>'resign'));
            $this->notify($obj->getEmployee(),$link,$message,$source);
        }
    }

	protected function getObjectLabel($object)
    {
        if ($object == null){
            return '';
        }
        return $object->getEmployee()->getDisplayName();
    }

    protected function newBaseClass() {
        return new Resign();
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
            $grid->newColumn('Date Filed','getDateFiled','date_filed','o',array($this,'formatDate')),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name','emp'),
            $grid->newColumn('Department', 'getDepartmentDisplay', 'employee'),
            $grid->newColumn('Supervisor(s)', 'getSupervisorDisplayName', 'employee'),
            $grid->newColumn('Status', 'getStatus', 'status'),            
        );
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisWorkforceBundle:Resign:action.html.twig',
            $params
        );
    }

    public function gridAction()
    {
        $gl = $this->setupGridLoader();
        $qry = array();

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();

        if($this->getUser()->getEmployee() != NULL)
        {
            $id = $this->getUser()->getEmployee()->getID();
            
            $groups = explode(',',$this->getUser()->getEmployee()->getUser()->getGroupsText()); 
            $qry[] = "(o.employee != '".$id."')";
            if(in_array('dept_head', array_map('trim',$groups)))
            {
                $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.supervisor = '".$id."'))";
            } 
        }       
        
        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gl->setQBFilterGroup($fg);
        }

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
}