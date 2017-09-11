<?php

namespace Hris\MemoBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\MemoBundle\Entity\Memo;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use DateTime;

class MemoController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo';
        $this->title = 'Memo';
        $this->list_title = 'Memos';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Memo();
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id'           => $id,
            'route_edit'   => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix'       => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render('HrisMemoBundle:Default:action.html.twig',$params);
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');
        $this->hookPreAction();
        $gl                   = $this->setupGridLoader();
        //$params               = $this->getViewParams('List', 'hris_memo_index');
        $twig_file            = 'HrisMemoBundle:Default:index.html.twig';
        $params               = $this->getViewParams('List');
        $params['list_title'] = $this->list_title;
        $params['grid_cols']  = $gl->getColumns();
        $params['add_button'] = $this->add_button;

        return $this->render($twig_file, $params);
    }

    protected function getViewParams($subtitle = '', $selected = null)
    {
        // default to list route if nothing selected
        if ($selected == null && $this->getRouteGen()->getList() != null)
            $selected = $this->getRouteGen()->getList();

        $params = parent::getViewParams($subtitle, $selected);

        $params['route_list']   = $this->getRouteGen()->getList();
        $params['route_add']    = $this->getRouteGen()->getAdd();
        $params['route_edit']   = $this->getRouteGen()->getEdit();
        $params['route_delete'] = $this->getRouteGen()->getDelete();
        $params['route_grid']   = $this->getRouteGen()->getGrid();
        $params['prefix']       = $this->route_prefix;
        $params['base_view']    = $this->base_view;

        return $params;
    }

    public function createSubmitAction()
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

    protected function update($o, $data, $is_new = false)
    {
        if ($is_new)
        {
            $o->setType($data['type']);
            $wf = $this->get('hris_workforce');
            if (isset($data['issued_to'])) 
            {
                $emp_obj = $wf->getEmployee($data['issued_to']);
            }
            else
            {
                $emp_obj = $wf->getEmployee($data['emp_id']);
            }
            $o->setEmployee($emp_obj);
        }

        $em = $this->getDoctrine()->getManager();

        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        
    }

    public function notifyEmployee($employee_id, $memo_id, $message)
    {
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');
        
        $emp_obj = $settings->getEmployee($employee_id);
        if ($emp_obj != null)
        {
            $event = new NotificationEvent();
            $notif_body = array(
                    'link'=> $this->generateUrl($this->route_prefix.'_print',array('id'=>$memo_id)),
                    'type'=> Notification::TYPE_ALERT);
            $notif_body['receipient']   = $emp_obj;
            $notif_body['message']      = $message;
            $notif_body['source']       = 'Memo received';
            $event->notify($notif_body);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }
    }

    public function notifyApprover($employee_id, $memo_id, $message)
    {
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');
        
        $emp_obj = $settings->getEmployee($employee_id);
        if ($emp_obj != null)
        {
            $event = new NotificationEvent();
            $notif_body = array(
                    'link'=> $this->generateUrl($this->route_prefix.'_edit_form',array('id'=>$memo_id)),
                    'type'=> Notification::TYPE_ALERT);
            $notif_body['receipient']   = $emp_obj;
            $notif_body['message']      = $message;
            $notif_body['source']       = 'Memo received';
            $event->notify($notif_body);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        return $params;
    }

    public function createMemoAction($type, $data_field)
    {
        $this->checkAccess($this->route_prefix . '.add');
        $params = $this->getViewParams('Add');
        $em = $this->getDoctrine()->getManager();
        $type       = $type;
        $issued_to  = '';

        if ($type == 'Promotion' || $type == 'Regularization')
        {
            
        }
        else
        {
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }

        $this->hookPreAction();
        $obj = $this->newBaseClass();

        $params['issued_by']  = $this->getUser()->getName();
        $settings             = $this->get('hris_settings');
        $params['dept_opts']  = $settings->getDepartmentOptions();
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['object']     = $obj;
        $params['type']       = $type;
        $params['readonly']   = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('HrisMemoBundle:Memo:add.html.twig', $params);
    }

  

    public function printMemoAction($id)
    {
        
    }

    protected function getObjectLabel($obj)
    {

    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Date Issued','getDateIssued','id'),
            $grid->newColumn('Type','getType','id'),
            $grid->newColumn('Issued by','getUserCreateName','user_create'),
            $grid->newColumn('Issued to','getEmployeeName','employee'),
        );
    }

    public function isContractualAction($id)
    {
        
        $wf = $this->get('hris_workforce');
        $emp_obj = $wf->getEmployee($id);
        $data = $emp_obj->toData();
        $data->employment_status = $emp_obj->getEmploymentStatus();

        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }


}
