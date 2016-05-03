<?php

namespace Hris\MemoBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Catalyst\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Hris\MemoBundle\Entity\Memo;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Hris\MemoBundle\Controller\MemoController as Controller;
use DateTime;

class CompanyMemoController extends Controller
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo_company';
        $this->title = 'Company Memo';
        $this->list_title = 'Company Memos';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisMemoBundle:Memo';
        $this->add_button = true;
    }

    protected function newBaseClass()
    {
        return new Memo();
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

    protected function update($o, $data, $is_new = false)
    {
       
        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
        $o->setType(Memo::TYPE_ALL);
        $arr = array(
            'type'            => "Company",
            'title'           => $data['title'],
            'to'     => $data['to'],
            'number' => $data['number'],
            'attn_to' => $data['attn_to'],
            'cc' => $data['cc'],
            'from' => $data['from'],
            'message' => $data['message'],
            );
        $o->setContent(json_encode($arr));
        $o->setDateIssued(new DateTime($data['issued_date']));

        if(isset($data['status_btn'])){
            switch($data['status_btn'])
            {
                case Memo::STATUS_FORREVIEW : 
                        $o->setStatus(Memo::STATUS_FORREVIEW);
                        $this->review($o);
                        break;
                case Memo::STATUS_REVIEWED : 
                        $o->setReviewedBy($this->getUser()->getEmployee());
                        $o->setStatus(Memo::STATUS_REVIEWED);
                        $this->noted($o);
                        break;
                case Memo::STATUS_NOTED : 
                        $o->setNotedBy($this->getUser()->getEmployee());
                        $o->setStatus(Memo::STATUS_NOTED);
                        $this->approve($o);
                        break;
                case Memo::STATUS_APPROVED : 
                        $o->setApprovedBy($this->getUser()->getEmployee());
                        $o->setStatus(Memo::STATUS_APPROVED);
                        $this->send($o);
                        break;
                case Memo::STATUS_SENT : 
                        $o->setStatus(Memo::STATUS_SENT);
                        $this->notifyAll($o);
                        break;

            }
        }

    }

    protected function review($o)
    {

        $message = "A Company Memo requires your review.";
        $notified = "hris_memo_reviewer";
        $this->notifyMemo($o, $notified,$message);
    }

    protected function noted($o)
    {
        $message = "A Company Memo requires your note.";
        $notified = "hris_memo_noted";
        $this->notifyMemo($o,$notified, $message);
    }


    protected function approve($o)
    {
        $message = "A Company Memo requires your approval.";
        $notified = "hris_memo_approver";
        $this->notifyMemo($o, $notified,$message);
    }

    protected function send($o)
    {
        $config = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');
        $hr = $settings->getDepartment($config->get('hris_hr_department'));
        //$emp_obj = $settings->getEmployee($employee_id);
        $message = "A Company Memo has been approved and is ready to be sent.";

        $event = new NotificationEvent();
        $notif_body = array(
                'link'=> $this->generateUrl($this->route_prefix.'_edit_form',array('id'=>$o->getID())),
                'type'=> Notification::TYPE_ALERT);
        $notif_body['receipient']   = $hr;
        $notif_body['message']      = $message;
        $notif_body['source']       = 'Memo received';
        $event->notify($notif_body);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    
    }

    protected function notifyMemo($o, $notified,$message)
    {
        $conf = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');

        $reviewers = $settings->getEmployeesByJobTitle($conf->get($notified));

       
        foreach ($reviewers as $reviewer) {
            $this->notifyApprover($reviewer->getID(), $o->getID(), $message);
        }
    }


    public function notifyAll($obj)
    {
        $message = json_decode($obj->getContent(),true);
        //$this->notifyAll($obj->getID(), $message['message']);
        $config = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');

        $event = new NotificationEvent();
        $notif_body = array(
                'link'=> $this->generateUrl('hris_memo_company_print',array('id'=>$obj->getID())),
                'type'=> Notification::TYPE_ALERT);
        $notif_body['message']      = "See attached company memo";
        $notif_body['source']       = 'Memo received';
        $event->notify($notif_body);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);

    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $params['type'] = "Company";
        $params['data'] = json_decode($o->getContent());

        if($o->getStatus() == Memo::STATUS_SENT){
            $params['readonly'] = true;
        }
        return $params;
    }

    protected function getObjectLabel($obj)
    {

    }

    protected function filterGrid()
    {
        $fg = parent::filterGrid();
        $fg->where('o.type = :type_id')
            ->setParameter('type_id', Memo::TYPE_ALL);
        return $fg;
    }


    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');

        return array(
            $grid->newColumn('Date Issued','getDateIssuedFormatted','id'),
            $grid->newColumn('Title','getContent','id','o', array($this,'getContentTitle')),
            $grid->newColumn('Status','getStatus','status'),
            $grid->newColumn('Issued by','getUserCreateName','user_create'),
        );
    }


    public function getContentTitle($content)
    {
        $data = json_decode($content,true);
        if(isset($data['title'])){
            return $data['title'];
        }else {
            return "";
        }
    }

    public function isContractualAction($id)
    {
        $wf = $this->get('hris_workforce');
        $emp_obj = $wf->getEmployee($id);
        $data = $emp_obj->toData();
        $data->employment_status = $emp_obj->getEmploymentStatus();
        // setup json response
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function printPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');
        $obj = $em->getRepository($this->repo)->find($id);
  
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

       
        $params['issued_by'] = $this->getUser()->getName();
        $params['obj'] = $obj;
        $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        $params['company_website'] = $conf->get('hris_com_info_website');

        if ($conf->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('CatalystContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $this->padFormParams($params, $obj);
        $twig = "HrisMemoBundle:CompanyMemo:print.html.twig";

        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }



}
