<?php

namespace Hris\WorkforceBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class AttendanceController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_workforce_attendance';
		$this->title = 'Attendance';

		$this->list_title = 'Attendance';
		$this->list_type = 'dynamic';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_workforce_attendance_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisWorkforceBundle:Attendance:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    public function viewAction()
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_workforce_attendance_view');

        $twig_file = 'HrisWorkforceBundle:Attendance:view.html.twig';

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;

        return $this->render($twig_file, $params);
    }
    public function update($o,$data,$is_new = false)
    {
        $this->updateTrackCreate($o,$data,$is_new);
        $date = new DateTime($data['date']);
        $start = new DateTime($data['start']);
        $end = new DateTime($data['end']);
        $em = $this->getDoctrine()->getManager();

        $am = $this->get('hris_attendance');

        $employee = $am->getEmployee($data['employee']);

        if($employee == null)
        {
            $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findOneBy(array('employee_code' => $data['employee'] )); 
        }

        $grace_period = $employee->getSchedule()->getGracePeriod();
        $half_day = $employee->getSchedule()->getHalfday();
        $schedule = $em->getRepository('HrisAdminBundle:Schedules')->find($employee->getSchedule());
        $department = $employee->getDepartment();
        $start_sched = $schedule->getStart();
        $end_sched = $schedule->getEnd();

        // // Check if late
        $time_in = $am->getLate($start_sched,$start);
        // Check if undertime
        $time_out = $am->getUndertime($end_sched,$end);

        $type = $am->checkHolidayType($employee,$date);
        $o->setStatus($type);

        if($time_in > 0)
        {
            if ($time_in <= $grace_period)
            {
                //within grace period
                $o->setLate(0);
            }
            else
            {
                if($time_in >= $half_day)
                {
                    $o->setStatus(Attendance::STATUS_HALFDAY);
                }
                $o->setLate($time_in);
            }
        }
        else
        {
            $o->setLate(0);
        }

        if($time_out < 0)
            $o->setUnderTime(abs($time_out));
        else
            $o->setUnderTime(0);

        $o->setTimeIn($start);
        $o->setTimeOut($end);

        if(isset($data['action_btn']))
        {
            switch($data['action_btn'])
            {
                case 'review':
                        $o->setAdjustmentStatus(Attendance::STATUS_REVIEW);
                        break;
                case 'approve':
                        $date = new DateTime();
                        //Check for late and undertime
                        $adj_in = $am->getLate($start_sched, $o->getAdjustTimeIn());
                        $adj_out = $am->getUndertime($end_sched, $o->getAdjustTimeOut());

                        if($adj_in <= $grace_period)
                        {
                            $o->setLate(0);
                        } 
                        else
                        {
                            $o->setLate($adj_in);
                        }

                        if($adj_out < 0)
                            $o->setUnderTime(abs($adj_out));
                        else
                            $o->setUnderTime(0);

                        if(isset($data['halfday']) && $data['halfday'] == 0 || $o->isHalfday())
                        {
                            $o->setHalfday(true);
                            $o->setStatus(Attendance::STATUS_HALFDAY);
                            $o->setUnderTime(0);
                        }

                        $o->setApprovedBy($this->getUser());    
                        $o->setDateApproved($date);
                        $o->setTimeIn($o->getAdjustTimeIn());
                        $o->setTimeOut($o->getAdjustTimeOut());
                        $o->setAdjustmentStatus(Attendance::STATUS_APPROVE);
                        break;

                case 'reject':
                        $date = new DateTime();

                        $o->setApprovedBy($this->getUser());
                        $o->setDateApproved($date);
                        $o->setAdjustmentStatus(Attendance::STATUS_REJECT);
                        break;
            }
        }
        if(isset($data['overtime_btn']))
        {
            switch($data['overtime_btn'])
            {
                case 'review':
                        $o->setOvertimeStatus(Attendance::STATUS_REVIEW);
                        break;
                case 'approve':
                        $date = new DateTime();
                        $o->setOvertime($o->getOvertimeTemp());
                        $o->setOvertimeApproved($date);
                        $o->setOvertimeStatus(Attendance::STATUS_APPROVE);
                                break;
                case 'reject':
                        $date = new DateTime();
                        $o->setOvertimeApproved($date);
                        $o->setOvertimeStatus(Attendance::STATUS_REJECT);
                                break;
            }
        }

        if($is_new) {
            $o->setEmployee($employee);
            $o->setDate($date);
        }
    }
	protected function getObjectLabel($obj)
    {
        if ($obj == null)
        {
            return '';
        }
        return $obj->getID();
    }

    protected function newBaseClass()
    {
        return new Attendance();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('emp','employee','getEmployee'),
        );
    }

    public function gridAttendancesAction($id = null, $department = null, $date_from = null, $date_to = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterAttendanceGrid($id,$department,$date_from,$date_to));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterAttendanceGrid($id = null, $department = null, $date_from = null, $date_to = null)
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        $date = new DateTime();

        $date_from = $date_from=='null'? new DateTime($date->format('Ym01')):new DateTime($date_from);
        $date_to = $date_to=='null'? new DateTime($date->format('Ymt')):new DateTime($date_to);

        $qry[] = "(o.date >= '".$date_from->format('Y-m-d')."' AND o.date <= '".$date_to->format('Y-m-d')."')";

        if($department != null and $department != 'null')
        {

            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$department."'))";
        }
        else if ($id != null and $id != 'null')
        {
            $qry[] = "(o.employee = '".$id."')";
        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Date','getDateDisplay','date'),
            $grid->newColumn('Employee','getDisplayName','last_name','emp'),
            $grid->newColumn('Time-In', 'getTimeIn', 'time_in'),
            $grid->newColumn('Time-Out', 'getTimeOut', 'time_out'),
            $grid->newColumn('Late', 'getLateDisplay', 'late'),
            $grid->newColumn('Undertime', 'getUnderTimeDisplay', 'undertime'),
            $grid->newColumn('Status', 'getStatus', 'status'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();

        foreach ($employee as $emp)
        {
            $emp_opts[$emp->getID()] = $emp->getDisplayName();
        }
        $dept_opts = [];
        $params['dept_opts'] = $settings->getDepartmentOptions();
        $int_opts = [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
        $params['interval'] = 'daily';
        $params['dept_id'] = 0;
        $params['emp_id'] = 0;
        $params['emp_opts'] = $emp_opts;
        $params['int_opts'] = $int_opts;


        return $params;
    }

    public function gridAttendanceAction($id = null, $date_from = null, $date_to = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('hris_attendance');
        $data = $em->getRepository('HrisWorkforceBundle:Attendance')->findBy(array('employee' => $id));

        $arr = [];
        foreach($data as $attendance)
        {
            $date = $attendance->getDate();
            $time_in = new DateTime($attendance->getTimeIn());
            $time_out = new DateTime($attendance->getTimeOut());
            $start = $date->format('Y-m-d').'T'.$time_in->format('H:i:s');
            $end = $date->format('Y-m-d').'T'.$time_out->format('H:i:s');

            $color = '#003b6f';

            if($am->isHoliday($date))
            {
                $color = '#008A00';
                if($attendance->getTimeIn() == null)
                {
                    if($attendance->getStatus() == Attendance::STATUS_HOLIDAY)
                    {
                    $arr[] = array('title' => Attendance::STATUS_HOLIDAY, 'start' => $date->format('Y-m-d'), 'backgroundColor' => $color, 'late' => null, 'undertime' => null);   
                    }
                    else
                    {
                    $arr[] = array('title' => Attendance::STATUS_ABSENTNONWORKING, 'start' => $date->format('Y-m-d'), 'backgroundColor' => $color, 'late' => null, 'undertime' => null); 
                    }
                }
                else
                {
                    $arr[] = array('start' => $start, 'end' => $end, 'backgroundColor' => $color, 'late' => $attendance->getLateDisplay(), 'undertime' => $attendance->getUnderTimeDisplay());     
                }

            }
            elseif ($attendance->getStatus() == Attendance::STATUS_NONWORKING)
            {
                $color = '#FFA500';
                $arr[] = array('title' => Attendance::STATUS_NONWORKING.' Day', 'start' => $date->format('Y-m-d'), 'backgroundColor' => $color, 'late' => null, 'undertime' => null);  
            }

            elseif($attendance->getStatus() == Attendance::STATUS_ABSENT)
            {

                $color = '#8B0C00';
                $arr[] = array('title' => Attendance::STATUS_ABSENT, 'start' => $date->format('Y-m-d'), 'backgroundColor' => $color, 'late' => null, 'undertime' => null);
            }
            elseif($attendance->getStatus() == Attendance::STATUS_PRESENT)
            {
                $arr[] = array('start' => $start, 'end' => $end, 'backgroundColor' => $color, 'late' => $attendance->getLateDisplay(), 'undertime' => $attendance->getUnderTimeDisplay()); 
            }
            elseif($attendance->getStatus() == Attendance::STATUS_HALFDAY)
            {
                $arr[] = array('start' => $start, 'end' => $end, 'backgroundColor' => '#F74B4B', 'late' => $attendance->getLateDisplay(), 'undertime' => $attendance->getUnderTimeDisplay()); 
            }
            elseif($attendance->getStatus() == Attendance::STATUS_DRAFT)
            {
                $arr[] = array('start' => $start, 'end' => $end, 'backgroundColor' => '#F74B4B', 'late' => $attendance->getLateDisplay(), 'undertime' => $attendance->getUnderTimeDisplay()); 
            }
            elseif($attendance->getStatus() == Attendance::STATUS_PAIDLEAVE)
            {
                $arr[] = array('title' => Attendance::STATUS_PAIDLEAVE, 'backgroundColor' => 'ff00f4', 'late' => null, 'undertime' => null); 
            }

        }

        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function importDTRAction()
    {
        $em = $this->getDoctrine()->getManager();
        $files = $this->getRequest()->files->get('file');
        $am = $this->get('hris_attendance');
        foreach ($files as $file) {
            try
            {
                $rows = $this->parseCSV($file);
                foreach ($rows as $row)
                {

                    $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findOneBy(array('employee_code' => $row['employee'] ));

                    if($employee == null)
                    {   
                        $this->addFlash('errorsuccess', 'Import of DTR successful.');
                        throw new ValidationException('Invalid Employee ID: '.$employee->getID());
                        break;
                    }
                    $date = new DateTime($row['date']);

                    // Check if Attendance entry generated
                    $attendance = $em->getRepository('HrisWorkforceBundle:Attendance')
                    ->findOneBy(
                        array('employee'=>$employee,
                            'date'=>$date)
                    );
                    if($attendance == null) {
                        $attendance = $this->newBaseClass();
                        $is_new = true;
                    }
                    else {
                        $is_new = false;
                    }
                    $this->update($attendance,$row,$is_new);
                    if($is_new)
                    $em->persist($attendance);
                }
                $em->flush();
                $this->scanAttendance();
                $this->addFlash('success', 'Import of DTR successful.');
            }
            catch (ValidationException $e)
            {
                // TODO: return error
                $this->addFlash('error', $e->getMessage());
            }
            catch (DBALException $e)
            {
                // TODO: return error
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->redirect($this->getRequest()->headers->get('referer'));
    }

    protected function parseCSV($files)
    {
        $em = $this->getDoctrine()->getManager();
        $data = new SplFileObject($files);
        $data->setFlags(SplFileObject::READ_CSV);
        $attendances = new LimitIterator($data, 1);
        $rows = [];
        foreach ($attendances as $attendance) {
            if(array_key_exists(1, $attendance))
            {
                $rows[] = array(
                    'employee' => $attendance[0],
                    'name' => $attendance[1],
                    'date' => $attendance[2],
                    'start' => $attendance[3],
                    'end' => $attendance[4]
                    );
            }
            else {
                break;
            }
        }
        return $rows;
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $wf = $this->get('hris_workforce');
        $conf = $this->get('gist_configuration');
        //check for tardiness >= 5 for the current month
        $report = $this->get('hris_attendance');
        $month = date('m');
        $year = date('Y');
        $date_compare = $year.'-'.$month.'-%';
        $attendance = $report->getTotalAttendance($obj->getEmployee()->getID(),$date_compare);
        $data = $report->getTotal($attendance);
        $total = $data['total_late'];

        if ($total >= 5)
        {
            $message = "Employee ".$obj->getEmployee()->getDisplayName()." has incurred more than 5 lates.";
            //send notif to create memo
            $this->notifyHR($message, 'Issue tardiness memo', $obj->getEmployee()->getID());
        }

        if($obj->getAdjustmentStatus() == Attendance::STATUS_REVIEW)
        {
            $request = 'Attendance Adjustment';
            $vp_opts = $wf->getEmployee($conf->get('hris_vp_operations'));
            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'Request for '.$request,
                'link'=> $this->generateUrl('hris_workforce_attendance_edit_form',array('id'=>$obj->getID())),
                'message'=> $obj->getEmployee()->getFirstName().' '.$obj->getEmployee()->getLastName().' filed for an Overtime',
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $vp_opts));

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }
        
        if($obj->getOvertimeStatus() == Attendance::STATUS_REVIEW)
        {
            $request = 'Overtime';
            $vp_opts = $wf->getEmployee($conf->get('hris_vp_operations'));
            $event = new NotificationEvent();
            $event->notify(array(
                'source'=> 'Request for '.$request,
                'link'=> $this->generateUrl('hris_workforce_attendance_edit_form',array('id'=>$obj->getID())),
                'message'=> $obj->getEmployee()->getFirstName().' '.$obj->getEmployee()->getLastName().' filed for an Overtime',
                'type'=> Notification::TYPE_UPDATE,
                'receipient' => $vp_opts));

            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('notification.event', $event);
        }
    }

    public function notifyHR($message, $source, $employee_id)
    {
        $config = $this->get('gist_configuration');
        $settings = $this->get('hris_settings');
        $hr_dept = $config->get('hris_hr_department');

        if ($hr_dept == null){}
        else
        {
            $hr = $settings->getDepartment($hr_dept);
            $hr = $hr->getDeptHead();

            if ($hr == null){}
            else
            {
                $event = new NotificationEvent();
                $notif_body = array(
                        'link'=> $this->generateUrl('hris_workforce_employee_edit_form',array('id'=>$employee_id)),
                        'type'=> Notification::TYPE_UPDATE);
                $notif_body['receipient']   = $hr;
                $notif_body['message']      = $message;
                $notif_body['source']       = $source;
                $event->notify($notif_body);
                $dispatcher = $this->get('event_dispatcher');
                $dispatcher->dispatch('notification.event', $event);
            }
        }
    }

    protected function scanAttendance()
    {
        $em = $this->getDoctrine()->getManager();
        $report = $this->get('hris_attendance');
        $month = date('m');
        $year = date('Y');
        $date_compare = $year.'-'.$month.'-%';
        $employees = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();

        foreach ($employees as $employee) 
        {
            $attendance = $report->getTotalAttendance($employee->getID(),$date_compare);
            $data = $report->getTotal($attendance);
            $total = $data['total_late'];

            if ($total >= 5)
            {
                $message = "Employee ".$employee->getDisplayName()." has incurred more than 5 lates.";
                //send notif to create memo
                $this->notifyHR($message, 'Issue tardiness memo', $employee->getID());
            }
        }
    }

    public function printAction($id = null, $dfrom = null, $dto = null, $dept = null)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisWorkforceBundle:Attendance:print.html.twig";

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

        $date = new DateTime();
        $date_from = $dfrom=='null'? new DateTime($date->format('Ym01')):new DateTime($dfrom);
        $date_to = $dto=='null'? new DateTime($date->format('Ymt')):new DateTime($dto);

        //params here
        if ($id == 0) 
        {
            if ($dept == 0) 
            {
                $query = $em            ->createQueryBuilder();
                $query                  
                                        ->from('HrisWorkforceBundle:Attendance', 'o')
                                        ->where('o.date >= :date_from and o.date <= :date_to')
                                        ->orderBy('o.date', 'ASC')
                                        ->setParameter('date_from', $date_from)
                                        ->setParameter('date_to', $date_to);
                                        
                $data = $query          ->select('o')
                                        ->getQuery()
                                        ->getResult();   
                $params['emp'] = null; 
                $params['dept'] = null;
            }
            else
            {
                $query = $em            ->createQueryBuilder();
                $query                  
                                        ->from('HrisWorkforceBundle:Attendance', 'o')
                                        ->join('HrisWorkforceBundle:Employee', 'e', 'WITH', 'o.employee=e.id')
                                        ->join('HrisAdminBundle:Department', 'd', 'WITH', 'e.department=d.id')
                                        ->where('o.date >= :date_from and o.date <= :date_to and d.id = :did')
                                        ->orderBy('o.date', 'ASC')
                                        ->setParameter('date_from', $date_from)
                                        ->setParameter('did', $dept)
                                        ->setParameter('date_to', $date_to);
                                        
                $data = $query          ->select('o')
                                        ->getQuery()
                                        ->getResult();   
                $params['emp'] = null; 
                $params['dept'] = $em->getRepository('HrisAdminBundle:Department')->find($dept);
            }                    
        }
        else
        {
            if ($dept == 0) 
            {
                $query = $em            ->createQueryBuilder();
                $query                  
                                        ->from('HrisWorkforceBundle:Attendance', 'o')
                                        ->where('o.date >= :date_from and o.date <= :date_to and o.employee = :id')
                                        ->orderBy('o.date', 'ASC')
                                        ->setParameter('date_from', $date_from)
                                        ->setParameter('date_to', $date_to)
                                        ->setParameter('id', $id);
                                        
                $data = $query          ->select('o')
                                        ->getQuery()
                                        ->getResult();   
                $params['emp'] = $em->getRepository('HrisWorkforceBundle:Employee')->find($id);
                $params['dept'] = null;
            }
            else
            {
                $query = $em            ->createQueryBuilder();
                $query                  
                                        ->from('HrisWorkforceBundle:Attendance', 'o')
                                        ->join('HrisWorkforceBundle:Employee', 'e', 'WITH', 'o.employee=e.id')
                                        ->join('HrisAdminBundle:Department', 'd', 'WITH', 'e.department=d.id')
                                        ->where('o.date >= :date_from and o.date <= :date_to and o.employee = :id and d.id = :did')
                                        ->orderBy('o.date', 'ASC')
                                        ->setParameter('date_from', $date_from)
                                        ->setParameter('date_to', $date_to)
                                        ->setParameter('did', $dept)
                                        ->setParameter('id', $id);
                                        
                $data = $query          ->select('o')
                                        ->getQuery()
                                        ->getResult();   
                $params['emp'] = $em->getRepository('HrisWorkforceBundle:Employee')->find($id);
                $params['dept'] = $em->getRepository('HrisAdminBundle:Department')->find($dept);
            }
        }

        $params['date_from_display'] = $date_from;
        $params['date_to_display'] = $date_to;

        $config               = $this  ->get('gist_configuration');
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



        $params['all'] = $data;
        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    
}