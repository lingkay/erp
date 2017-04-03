<?php

namespace Hris\ProfileBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Doctrine\ORM\EntityManager;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;

use Hris\WorkforceBundle\Entity\Attendance;

use DateTime;

class AdjustmentController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_profile_attendance';
        $this->title = 'Attendance Adjustment';

        $this->list_title = 'Attendance Adjustmenet';
        $this->list_type = 'dynamic';
        // $this->repo = 'HrisWorkforceBundle:Request';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        return $this->render('HrisProfileBundle:Adjustment:index.html.twig', $params);
    }

    protected function getObjectLabel($obj) {
        if ($obj == null)
            return '';
        return $obj->getID();
    }

    protected function newBaseClass() {
        return new Request();
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        if(isset($data['adjustment_type']))
        {
            foreach ($data['adjustment_type'] as $key => $value) {
                switch ($value) {
                    case 'attendance':
                        $this->updateAttendance($o,$data,$is_new);
                        break;
                    case 'overtime':
                        $this->updateOvertime($o,$data,$is_new);
                        break;
                }
            }
        }
    }

    protected function updateAttendance($o,$data,$is_new)
    {
        $setting = $this->get('hris_settings');
        $media = $this->get('gist_media');
        $adj_in = new DateTime($data['adj_time_in']);
        $adj_out = new DateTime($data['adj_time_out']);

        $date_request = new DateTime();
        $o->setAdjustmentDate($date_request);
        $o->setAdjustTimeIn($adj_in);
        $o->setAdjustTimeOut($adj_out);
        $o->setAdjustmentStatus(Attendance::STATUS_DRAFT);

        if(isset($data['halfday']) && $data['halfday'] == 0)
        {
            $o->setHalfday(true);
        }

        if(isset($data['file']) && $data['file']!=0 && $data['file'] != ""){
            $o->setUpload($media->getUpload($data['file']));
        }

        $o->setReason($data['reason']);
    }

    protected function updateOvertime($o,$data,$is_new)
    {
        $date = new DateTime($data['overtime_request']);

        $o->setOvertimeDate($date);
        $o->setOvertimeTemp($data['total_hrs']);
        $o->setOvertimeReason($data['overtime_reason']);
        $o->setOvertimeStatus(Attendance::STATUS_DRAFT);
    }
    public function checkAttendanceAction($id = null, $date = null)
    {
        $payroll = $this->get('hris_payroll');
        $setting = $this->get('hris_settings');
        $em = $this->getDoctrine()->getManager();

        $employee = $setting->getEmployee($id);
        $date = new DateTime($date);
        $attendance = $em->getRepository('HrisWorkforceBundle:Attendance')
                           ->findOneBy(array(
                            'employee'=>$employee, 
                            'date'=>$date)
                           );

        $payroll_lock = $payroll->dayLocked($employee, $date);
        
        $arr = [];
        if($attendance != null AND $payroll_lock)
        {
            $arr['isLocked'] = true;
        }
        else
        {
            $arr['isLocked'] = false;
            $arr['time_in'] = $attendance->getTimeIn();
            $arr['time_out'] = $attendance->getTimeOut();
        }
        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;    
    }

    public function checkRequestAction($id = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('hris_attendance');
        $data = $em->getRepository('HrisWorkforceBundle:Attendance')->findBy(array('employee' => $id));

        $arr = [];
        foreach($data as $attendance)
        {
            if($attendance->getAdjustmentStatus() == Attendance::STATUS_DRAFT && $attendance->getAdjustmentStatus() != NULL)
            {
                $date = $attendance->getDate();
                $time_in = $attendance->getAdjustTimeIn();
                $time_out = $attendance->getAdjustTimeOut();
                $start = $date->format('Y-m-d').'T'.$time_in->format('H:i:s');
                $end = $date->format('Y-m-d').'T'.$time_out->format('H:i:s');

                $color = '#D49C6A';

                $arr[] = array('title' => 'Adjustment Request', 'start' => $start, 'end' => $end, 'backgroundColor' => $color);     
            }    
        }

        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function checkOvertimeAction($id = null, $date = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('hris_attendance');
        $payroll = $this->get('hris_payroll');
        $conf = $this->get('gist_configuration');
        $employee = $am->getEmployee($id);
        $date = new DateTime($date);
        $attendance = $em->getRepository('HrisWorkforceBundle:Attendance')
                           ->findOneBy(array(
                            'employee'=>$employee, 
                            'date'=>$date)
                           );

        $schedule = $em->getRepository('HrisAdminBundle:Schedules')->find($employee->getSchedule());
        $end_sched = $schedule->getEnd();
        $end = new DateTime($attendance->getTimeOut());
        $ot_minutes = $am->getUndertime($end_sched,$end);
        $ot_threshold = '';
        if($conf->get('hris_setting_overtime_threshold') != null)
        {
            $ot_threshold = $conf->get('hris_setting_overtime_threshold');    
        }
        $ot_allowed = '';
        if($conf->get('hris_setting_overtime_groups') != null)
        {
            $allowed = json_decode($conf->get('hris_setting_overtime_groups'));
        }
        $ot_allowed = false;

        foreach ($allowed as $position => $group) {
            if($employee->getJobLevel()->getID() == $position)
            {
                foreach ($group as $key => $value) {
                    if($employee->getEmploymentStatus() == $key)
                    {
                        $ot_allowed = true;
                    }
                }
            }
        }

        $payroll_lock = $payroll->dayLocked($employee, $date);
        $arr = [];
        $ot = floor($ot_minutes/$ot_threshold);
        $arr['ot_allowed'] = $ot_allowed;
        if(!$payroll_lock AND $ot_allowed AND $ot_minutes >= $ot_threshold)
        {
            $arr['overtime'] = $ot;
        }
        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
    public function adjustmentAddAction($id = null, $date = null)
    {
        try
        {
            $setting = $this->get('hris_settings');
            $em = $this->getDoctrine()->getManager();
            $data = $this->getRequest()->request->all();
            $employee = $setting->getEmployee($id);
            $date = new DateTime($date);
            $attendance = $em->getRepository('HrisWorkforceBundle:Attendance')
                           ->findOneBy(array(
                            'employee'=>$employee, 
                            'date'=>$date)
                           );
            $obj = $attendance;
            // validate
            $this->validate($data, 'add');

            // update db
            $this->update($obj, $data);

            $em->flush();
            $this->hookPostSave($obj,true);
            $odata = $obj->toData();

            $res_data = new \stdClass();
            $res_data->data = $odata;
            $res_data->success = true;

            return new JsonResponse($res_data);
        }
        catch (ValidationException $e)
        {
            // TODO: return error
            error_log('validation error' . $e->getMessage());
        }
        catch (DBALException $e)
        {
            // TODO: return error
            error_log('dbal exception: ' . $e->getMessage());
        }
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $settings = $this->get('hris_settings');
        $config = $this->get('gist_configuration');
        if($obj->getAdjustmentStatus() != null and $obj->getAdjustmentStatus() == Attendance::STATUS_DRAFT)
        {
            $request = 'Attendance Adjustment';
        }
        
        if($obj->getOvertimeStatus() != null and $obj->getOvertimeStatus() == Attendance::STATUS_DRAFT)
        {
            $request = 'Overtime';
        }


        $hr = $settings->getDepartment($config->get('hris_hr_department'));

        $event = new NotificationEvent();
        $event->notify(array(
            'source'=> 'Request for '.$request,
            'link'=> $this->generateUrl('hris_workforce_attendance_edit_form',array('id'=>$obj->getID())),
            'message'=> $obj->getEmployee()->getFirstName().' '.$obj->getEmployee()->getLastName().' filed an '.$request,
            'type'=> Notification::TYPE_UPDATE,
            'receipient' => $hr));

        $dispatcher = $this->get('event_dispatcher');
        $dispatcher->dispatch('notification.event', $event);
    } 
}