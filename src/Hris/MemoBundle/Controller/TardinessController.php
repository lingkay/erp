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

class TardinessController extends Controller
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo_tardiness';
        $this->title = 'Notice of Tardiness';
        $this->list_title = 'Notice of Tardiness';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisMemoBundle:Memo';
        $this->add_button = false;
    }

    protected function update($o, $data, $is_new = false)
    {
        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
        $o->setType(Memo::TYPE_TARDINESS);

        if ($is_new)
        {
            $o->setType($data['type']);
            $o->setStatus(Memo::STATUS_APPROVED);
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

            $arr = array(
            'type'        => $data['type'],
            'message'     => $data['message'],
            'consequence' => $data['consequence'],
            'month'       => $data['month'],
            'year'        => $data['year']
            );
            $o->setContent(json_encode($arr));

            
        }
        else
        {
            // $arr = array(
            // 'type'        => $o->getType(),
            // 'message'     => $data['message'],
            // 'consequence' => $data['consequence'],
            // 'month'       => $data['month'],
            // 'year'        => $data['year']
            // );
            // $o->setContent(json_encode($arr));
        }
        

    }

    public function createTardinessMemoAction($data_field)
    {
        $this->checkAccess($this->route_prefix . '.add');
        $params = $this->getViewParams('Add');
        $em = $this->getDoctrine()->getManager();

        list($emp_id, $month, $year) = explode(",", $data_field);
        $report = $this->get('hris_attendance');
        if($month < 10)
        {
            $month = '0' . $month;
        }
        $date_compare          = $year.'-'.$month.'-%';
        $attendance            = $report->getTotalAttendance($emp_id,$date_compare);
        $params['dates_tardy'] = $report->getDatesTardy($attendance);
        $params['month']       = $month;
        $params['year']        = $year;
        $emp_obj              = $em->getRepository('HrisWorkforceBundle:Employee')->find($emp_id);
        $params['employee']   = $emp_obj;
       
        $this->hookPreAction();
        $obj = $this->newBaseClass();

        $params['issued_by']  = $this->getUser()->getName();
        $settings             = $this->get('hris_settings');
        $params['dept_opts']  = $settings->getDepartmentOptions();
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['object']     = $obj;
        $params['readonly']   = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);

        return $this->render('HrisMemoBundle:Tardiness:add.html.twig', $params);
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $content = json_decode($o->getContent(), true);
       
        $params['type'] = Memo::TYPE_TARDINESS;
        $params['message']     = $content['message'];
        $params['consequence'] = $content['consequence'];
        $report                = $this->get('hris_attendance');
        $month                 = $content['month'];
        $year                  = $content['year'];
        $date_compare          = $year.'-'.$month.'-%';
        if ($o->getID() != null) 
        {
            $attendance            = $report->getTotalAttendance($o->getEmployee()->getID(),$date_compare);
            $params['dates_tardy'] = $report->getDatesTardy($attendance);
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
            ->setParameter('type_id', Memo::TYPE_TARDINESS);
        return $fg;
    }


    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');

        return array(
            $grid->newColumn('Date Issued','getDateIssued','id'),
            $grid->newColumn('Issued To','getEmployeeName','employee'),
            $grid->newColumn('Issued by','getUserCreateName','user_create'),
        );
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
        $twig = "HrisMemoBundle:Tardiness:print.html.twig";

        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $event = new NotificationEvent();
        $notif_body = array(
                'link'=> $this->generateUrl('hris_memo_tardiness_edit_form',array('id'=>$obj->getID())),
                'type'=> Notification::TYPE_ALERT);
        $notif_body['receipient']   = $obj->getEmployee();
        $notif_body['message']      = "You have been issued a tardiness memo";
        $notif_body['source']       = 'Memo received';
        $event->notify($notif_body);
        $dispatcher = $this->container->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    }


}
