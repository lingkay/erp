<?php

namespace Hris\MemoBundle\Controller;


use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\MemoBundle\Entity\Memo;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Hris\MemoBundle\Controller\MemoController as Controller;
use DateTime;

class ViolationController extends Controller
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_memo_violation';
        $this->title = 'Notice of Violation';
        $this->list_title = 'Notice of Violation';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisMemoBundle:Memo';
        $this->add_button = true;
    }

    protected function update($o, $data, $is_new = false)
    {
        $this->updateTrackCreate($o,$data,$is_new);
        $settings = $this->get('hris_settings');
        $o->setType(Memo::TYPE_VIOLATION);
        $o->setDateIssued(new DateTime($data['issued_date']));

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
        if (isset($data['agency'])) {
                if ($data['agency'] === "Employee not under agency") 
                {
                    $agency = 0;
                }
                else
                {
                    $agency = $data['agency'];
                }
            }
            else {
                $agency = 0;
            }
        $arr = array(
            'type'                => $data['type'],
            'date_of_incident'    => $data['date_of_incident'],
            'location'            => $data['location'],
            'violation_committed' => $data['violation_committed'],
            'violated_policy'     => $data['violated_policy'],
            'agency'              => $agency
            );
        $o->setContent(json_encode($arr));

    }


 

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $content = json_decode($o->getContent(), true);
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');

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
        
        $params['is_hr'] = $this->checkRole('hr_admin');
        $params['type'] = "Violation";
        $params['date_of_incident']    = $content['date_of_incident'];
        $params['location']            = $content['location'];
        $params['violation_committed'] = $content['violation_committed'];
        $params['violated_policy']     = $content['violated_policy'];
        $params['agency']              = $content['agency'];
        $ops_vp_obj = $config->get('hris_vp_operations');
        $params['ops_vp_obj'] = $settings->getEmployee($ops_vp_obj);

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
            ->setParameter('type_id', Memo::TYPE_VIOLATION);
        return $fg;
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Date Issued','getDateIssuedFormatted','id'),
            $grid->newColumn('Issued To','getEmployeeName','employee'),
            $grid->newColumn('Issued by','getUserCreateName','user_create'),
        );
    }

    public function printPdfAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
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
            $params['company_address'] = $em->getRepository('GistContactBundle:Address')->find($conf->get('hris_com_info_company_address'));
        }

        $this->padFormParams($params, $obj);
        $twig = "HrisMemoBundle:Violation:print.html.twig";

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function updateStatusAction($id = null, $type = null, $status = null)
    {
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $memo_object = $em->getRepository('HrisMemoBundle:Memo')->find($id);
        $memo_object->setStatus($status);
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
                    if ($emp_obj->getEmploymentStatus() == "Contractual") 
                    {
                        $vp = $settings->getEmployeesByJobTitle($config->get('hris_vp_operations'));
                        if (!empty($vp)) {
                            foreach ($vp as $veep) {
                                $wf->notifyForApproval($ops_vp, $this->generateUrl('hris_memo_violation_edit_form',array('id'=>$id)));
                            }
                        }
                    }
                    elseif ($emp_obj->getEmploymentStatus() == "Regular") 
                    {
                        $wf->notifyForApproval($supervisor_obj, $this->generateUrl('hris_memo_violation_edit_form',array('id'=>$id)));
                    }
                }
            }
        }
        elseif ($status == 'Reviewed') 
        {
            $emp_obj = $settings->getEmployee($memo_object->getEmployee()->getID());
            if ($emp_obj != null)
            {   
                $wf->notifyForApproval($emp_obj, $this->generateUrl('hris_memo_violation_edit_form',array('id'=>$id)), 'You have been issued a violation memo');
                //$this->notifyForApproval($emp_obj, $id, 'hris_memo_violation_edit_form', 'You have been issued a violation memo'); 
            }
        }
        $this->addFlash('success', 'Memo updated successfully.');
        return $this->redirect($this->generateUrl('hris_memo_violation_edit_form', array('id' => $id)).$this->url_append);
    }
}
