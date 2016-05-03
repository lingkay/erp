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

class DisciplinaryController extends Controller
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo_disciplinary';
        $this->title = 'Notice of Disciplinary Action';
        $this->list_title = 'Notice of Disciplinary Action';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisMemoBundle:Memo';
        $this->add_button = true;
    }

    protected function update($o, $data, $is_new = false)
    {
        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
        $o->setType(Memo::TYPE_DISCIPLINARY);


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
        $arr = array(
                'type'                => $data['type'],
                'date_of_incident'    => $data['date_of_incident'],
                'location'            => $data['location'],
                'violation_committed' => $data['violation_committed'],
                'violated_policy'     => $data['violated_policy'],
                'action'              => $data['action'],
                'reason'              => $data['reason'],
                'date_of_exp'         => $data['date_of_exp']
                );


                $arr['date_start'] = $data['date_start'];
                $arr['date_end'] = $data['date_end'];
                $arr['other_actions'] = $data['other_actions'];
            

            $o->setContent(json_encode($arr));

    }


 

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $content = json_decode($o->getContent(), true);
        
        $groups = $this->getUser()->getGroups();
        if ($o->getEmployee() != null) 
        {
            if ($o->getEmployee()->getSupervisor() == null)
            {
                $params['is_supervisor'] = false;
            }
            else
            {
                if ($this->getUser()->getID() == $o->getEmployee()->getSupervisor()->getUser()->getID()) 
                {
                    $params['is_supervisor'] = true;
                }
                else
                {
                    foreach ($groups as $group) 
                    {
                        if ($group->getName() === "vp_operations") 
                        {
                            $params['is_supervisor'] = true;
                            break;
                        }
                        else
                        {
                            $params['is_supervisor'] = false;
                        }
                    }
                }
            }
        }
        $params['type'] = Memo::TYPE_DISCIPLINARY;
        $params['reason_opts'] = array(0=>'Unsatisfactory explanation', 1=>'Failure to submit explanation');
        $params['action_opts'] = array(0=>'Written warning', 1=>'Suspension', 2=>'Others');
        $params['date_of_incident']    = $content['date_of_incident'];
        $params['location']            = $content['location'];
        $params['violation_committed'] = $content['violation_committed'];
        $params['violated_policy']     = $content['violated_policy'];
        $params['date_start']          = $content['date_start'];
        $params['date_end']            = $content['date_end'];
        $params['other_actions']       = $content['other_actions'];
        $params['reason']              = $content['reason'];
        $params['date_of_exp']         = $content['date_of_exp'];
        $params['action']              = $content['action'];
        $params['other_actions']              = $content['other_actions'];
        $params['data'] = json_decode($o->getContent());
        $params['is_hr'] = $this->checkRole('hr_admin');
        return $params;
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
            ->setParameter('type_id', Memo::TYPE_DISCIPLINARY);
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
        $twig = "HrisMemoBundle:Disciplinary:print.html.twig";

        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function updateStatusAction($id = null, $type = null, $status = null)
    {
        $config = $this->get('catalyst_configuration');
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $memo_object = $em->getRepository('HrisMemoBundle:Memo')->find($id);
        $memo_object->setStatus($status);
        $hr_dept = $config->get('hris_hr_department');
        $hr = $settings->getDepartment($hr_dept);
        $hr = $hr->getDeptHead();
        $em->flush();

        if ($status == 'Sent') 
        {     
            $emp_obj = $settings->getEmployee($memo_object->getEmployee()->getID());
            if ($emp_obj != null)
            {
                $supervisor_obj = $emp_obj->getSupervisor();
                if ($supervisor_obj == null)
                {
                    $hr_dept = $config->get('hris_hr_department');
                    if ($hr_dept != null)
                    {
                        $hr = $settings->getDepartment($hr_dept);
                        $hr_head = $hr->getDeptHead();
                        if ($hr != null)
                        {
                            $wf->notifyForApproval($hr_head, $this->generateUrl('hris_memo_violation_edit_form',array('id'=>$id)));
                        }
                        else
                        {
                            $hr_admin = $wf->getEmployees(array('department'=>$hr->getID()));
                            foreach ($hr_admin as $hr_emp) {
                                if ($hr_emp->getUser()->hasGroup('hr_admin')) {
                                    $wf->notifyForApproval($hr_emp, $this->generateUrl('hris_memo_violation_edit_form',array('id'=>$id)));
                                }
                            }
                        }
                    }
                }
                else
                {
                    $wf->notifyForApproval($supervisor_obj, $this->generateUrl('hris_memo_disciplinary_edit_form',array('id'=>$id)));
                    //$this->notifyForApproval($supervisor_obj, $id, 'hris_memo_disciplinary_edit_form');
                }
            }
        }
        elseif ($status == 'Reviewed') 
        {
            $emp_obj = $settings->getEmployee($memo_object->getEmployee()->getID());
            if ($emp_obj != null)
            {   
                $wf->notifyForApproval($emp_obj, $this->generateUrl('hris_memo_disciplinary_edit_form',array('id'=>$id)), 'You have been issued a disciplinary memo');
            }
        }
        $this->addFlash('success', 'Memo updated successfully.');
        return $this->redirect($this->generateUrl('hris_memo_disciplinary_edit_form', array('id' => $id)).$this->url_append);
    }
}
