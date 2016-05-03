<?php

namespace Hris\MemoBundle\Controller;


use Catalyst\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use Hris\MemoBundle\Entity\Memo;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Hris\MemoBundle\Controller\MemoController as Controller;
use DateTime;

class PromotionController extends Controller
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo_promotion';
        $this->title = 'Notice of Promotion';
        $this->list_title = 'Notice of Promotion';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisMemoBundle:Memo';
        $this->add_button = false;
    }

    protected function update($o, $data, $is_new = false)
    {
        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
        $o->setType(Memo::TYPE_PROMOTION);


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
        $pos_to = $settings->getJobTitle($data['position_to']);
        $dep_to = $settings->getDepartment($data['department_to']);
        $job_to = $settings->getJobLevel($data['joblevel_to']);
        $arr = array(
            'type'            => $data['type'],
            'position_from'   => $data['position_from'],
            'position_to'     => $pos_to->getName(),
            'department_from' => $data['department_from'],
            'department_to'   => $dep_to->getName(),
            'joblevel_from'   => $data['joblevel_from'],
            'joblevel_to'     => $job_to->getName()
            );
        $o->setContent(json_encode($arr));

    }

    public function createPromotionMemoAction($emp_id)
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();
        $em = $this->getDoctrine()->getManager();
        $emp_obj              = $em->getRepository('HrisWorkforceBundle:Employee')->find($emp_id);
        


        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;
        $params['employee']   = $emp_obj;
        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('HrisMemoBundle:Promotion:add.html.twig', $params);
    }


 

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
          $settings = $this->get('hris_settings');
        $content = json_decode($o->getContent(), true);
       
        $params['type'] = Memo::TYPE_PROMOTION;
        $params['dept_opts']  = $settings->getDepartmentOptions();
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
    
        $params['position_from']   = $content['position_from'];
        $params['position_to']     = $content['position_to'];
        $params['department_from'] = $content['department_from'];
        $params['department_to']   = $content['department_to'];
        $params['joblevel_from']   = $content['joblevel_from'];
        $params['joblevel_to']     = $content['joblevel_to'];

        $params['is_hr'] = $this->checkRole('hr_admin');
        $params['is_vp'] = $this->checkRole('vp_operations');
        $params['is_president'] = $this->checkRole('hr_admin');
    
    }

    protected function checkRole($role)
    {
        $groups = $this->getUser()->getGroups();
        foreach ($groups as $group) 
        {
            if ($group->getName() === $role) 
            {
                return true;
                break;
            }
            else
            {
                
            }
        }
        return false;
    }

    protected function getObjectLabel($obj)
    {

    }

    protected function filterGrid()
    {
        $fg = parent::filterGrid();
        $fg->where('o.type = :type_id')
            ->setParameter('type_id', Memo::TYPE_PROMOTION);
        return $fg;
    }


    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');

        return array(
            $grid->newColumn('Date Issued','getDateIssued','id'),
            $grid->newColumn('Issued To','getEmployeeName','employee'),
             $grid->newColumn('Promoted To','getContent','id','o', array($this,'formatPromotion')),
            $grid->newColumn('Issued by','getUserCreateName','user_create'),
        );
    }

    public function formatPromotion($content)
    {
        $data = json_decode($content, true);
        return $data['position_to'];
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
        $params['object'] = $obj;
        $params['company_name'] = strtoupper($conf->get('hris_com_info_company_name'));
        $params['company_website'] = $conf->get('hris_com_info_website');

        if ($conf->get('hris_com_info_company_address') != null) 
        {
            $params['company_address'] = $em->getRepository('CatalystContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $this->padFormParams($params, $obj);
        $twig = "HrisMemoBundle:Promotion:print.html.twig";

        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }


    public function updateStatusAction($id = null, $type = null, $status = null)
    {
        $config = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');

        $em = $this->getDoctrine()->getManager();
        $memo_object = $em->getRepository('HrisMemoBundle:Memo')->find($id);
        $memo_object->setStatus($status);
        $em->flush();

        if ($status == 'Sent') 
        {
            $vp = $settings->getEmployeesByJobTitle($config->get('hris_vp_operations'));
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            if (!empty($vp)) {
                foreach ($vp as $veep) {
                    $this->notifyForApproval($veep, $id, 'hris_memo_promotion_edit_form');
                }
            }
            else
            {
                $this->notifyForApproval($hr, $id, 'hris_memo_promotion_edit_form');
            }
        }
        elseif ($status == 'Reviewed') 
        {
            $ceo = $settings->getEmployeesByJobTitle($config->get('hris_president'));
            $hr = $settings->getDepartment($config->get('hris_hr_department'));

            if (!empty($ceo)) {
                foreach ($ceo as $pres) {
                    $this->notifyForApproval($pres, $id, 'hris_memo_promotion_edit_form');
                }
            }
            else
            {
                $hr_admin = $wf->getEmployees(array('department'=>$hr->getID()));
                foreach ($hr_admin as $hr_emp) {
                    if ($hr_emp->getUser()->hasGroup('hr_admin')) {
                        $this->notifyForApproval($hr_emp, $id, 'hris_memo_promotion_edit_form');
                    }
                }
            }
        }
        elseif ($status == 'Approved') 
        {
            $emp_obj = $settings->getEmployee($memo_object->getEmployee()->getID());
            if ($emp_obj != null)
            {   
                $this->notifyForApproval($emp_obj, $id, 'hris_memo_promotion_edit_form', 'You have been issued a promotion memo'); 
            }
        }
        $this->addFlash('success', 'Memo updated successfully.');
        return $this->redirect($this->generateUrl('hris_memo_promotion_edit_form', array('id' => $id)).$this->url_append);
    }

    public function notifyForApproval($emp_obj, $memo_id, $route, $message = 'Your approval is required for this memo.')
    {
        $event = new NotificationEvent();
        $notif_body = array(
                'link'=> $this->generateUrl($route,array('id'=>$memo_id)),
                'type'=> Notification::TYPE_ALERT);
        $notif_body['receipient']   = $emp_obj;
        $notif_body['message']      = $message;
        $notif_body['source']       = 'Memo received for review';
        $event->notify($notif_body);
        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }
}
