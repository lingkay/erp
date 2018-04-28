<?php

namespace Hris\ToolsBundle\Controller;

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

class ScheduleController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_tools_schedule';
        $this->title = 'Schedule';

        $this->list_title = 'Schedule';
        $this->list_type = 'static';
        $this->repo = "HrisWorkforceBundle:Attendance";
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_tools_schedule_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisToolsBundle:Schedule:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['att_status_opts'] = array('\'Present\'' => 'Present', '\'Absent\'' => 'Absent', '\'Paid Leave\'' => 'Paid Leave', '\'Unpaid Leave\'' => 'Unpaid Leave', '\'Non-Working Holiday\'' => 'Non-Working Holiday', '\'Absent on Non-Working Holiday\'' => 'Absent on Non-Working Holiday', '\'Non-Working Holiday and Rest Day\'' => 'Non-Working Holiday and Rest Day', '\'Holiday\'' => 'Holiday', '\'Holiday and Rest day\'' => 'Holiday and Rest day', '\'Double Holiday\'' => 'Double Holiday', '\'Double Holiday and Rest day\'' => 'Double Holiday and Rest day', '\'Non-Working\'' => 'Non-Working', '\'Halfday\'' => 'Halfday');
        $params['title_opts'] = $settings->getJobTitleOptions();
        $params['level_opts'] = $settings->getJobLevelOptions();
        $params['status_opts'] = $settings->getEmploymentStatusOptions();
        $params['location_opts'] = $settings->getLocationOptions();
        $params['account_opts'] = array(1 => 'Enabled', 0=>'Disabled');
        $params['sched_opts'] = $settings->getSchedulesOptions();
        $params['gender_opts'] = array('\'Male\'' => 'Male', '\'Female\'' => 'Female');
        $params['day_opts'] = array('\'Monday\'' => 'Monday', '\'Tuesday\'' => 'Tuesday', '\'Wednesday\''=>'Wednesday', '\'Thursday\''=>'Thursday', '\'Friday\''=>'Friday', '\'Saturday\''=>'Saturday', '\'Sunday\''=>'Sunday');
        return $this->render($twig_file, $params);
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'HrisReportBundle:Attendance:action.html.twig',
            $params
        );
    }

    public function viewAction()
    {
        $this->hookPreAction();
        $gl = $this->setupGridLoader();
        $params = $this->getViewParams('List', 'hris_report_attendance_view');
        $twig_file = 'HrisReportBundle:Attendance:view.html.twig';
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
        return new AttendanceReport();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('emp','employee','getEmployee'),
        );
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
        $dept_opts = array();
        $emp_opts = array();

        foreach ($employee as $emp)
        {
            $emp_opts[$emp->getID()] = $emp->getDisplayName();
        }

        $params['dept_opts'] = $dept_opts + $settings->getDepartmentOptions();
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
        $params['location_opts'] = $settings->getLocationOptions();


        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {

    }

    private function getOutputData($id = null, $date_from = null, $date_to = null, $department = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location = null)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new DateTime();
        $date_from = $date_from==null? new DateTime($date->format('Ym01')):new DateTime($date_from);
        $date_to = $date_to==null? new DateTime($date->format('Ymt')):new DateTime($date_to);

        $query = $em    ->createQueryBuilder();
        $query          ->from('HrisWorkforceBundle:Attendance', 'o');
        $query          ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee=e.id');

        //date range filter
        $query          ->where('o.date >= :date_from');
        $query          ->andwhere('o.date <= :date_to');
        $query          -> setParameter('date_from', $date_from);
        $query          ->setParameter('date_to', $date_to);

        //employee filter
        if($id != null and $id != 'null' and $id != 0)
        {
            $query      ->andwhere("e.id = '".$id."'");
        }

        //department filter
        if($department != null and $department != 'null' and $department != 0 and $department != '0')
        {
            $query      ->andwhere("e.department = '".$department."'");
        }

        //location filter
        if($location != null and $location != 'null' and $location != 0)
        {
            $query      ->andwhere("e.location = '".$location."'");
        }

        //position filter
        if($position != null and $position != 'null' and $position != 0)
        {
            $query      ->andwhere("e.job_title = '".$position."'");
        }

        //rank filter
        if($rank != null and $rank != 'null' and $rank != 0)
        {
            $query      ->andwhere("e.job_level = '".$rank."'");
        }

        //gender filter
        if ($gender != 'null')
        {
            if ($gender != null)
            {
                $query ->andwhere ("e.gender IN (".$gender.")");
            }
        }

        //attendance status filter
        if ($status != 'null')
        {
            if ($status != null)
            {
                $query -> andwhere("o.status = ".$status."");
            }
        }

        //late filter
        if ($late != '0')
        {
            if ($late != 0)
            {
                $query -> andwhere("o.late >= ".$late." AND o.late <= ".$late_to."");
            }
        }

        //undertime filter
        if ($undertime != '0')
        {
            if ($undertime != 0)
            {
                $query -> andwhere("o.undertime >= ".$undertime." AND o.undertime <= ".$undertime_to."");
            }
        }

        $query ->orderBy('o.date', 'ASC');

        $data = $query          ->select('o')
            ->getQuery()
            ->getResult();

        return $data;
    }

    public function headers()
    {
        $headers = [
            'Date',
            'Employee',
            'Time-In',
            'Time-Out',
            'Late',
            'Undertime',
            'Status',
        ];
        return $headers;
    }

    public function csvAction($id = null, $date_from = null, $date_to = null, $department = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location = null)
    {

        $data = $this->getOutputData($id, $date_from, $date_to, $department, $position, $rank, $gender, $days, $late, $late_to, $undertime, $undertime_to, $status, $location);
        $date = new DateTime();
        $filename = 'Attendance Report.csv';

        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

        $i=0;
        foreach ($data as $d)
        {
            $arr_data = [
                $d->getDateDisplay(),
                $d->getEmployee()->getDisplayName(),
                $d->getTimeIn(),
                $d->getTimeOut(),
                $d->getLateDisplay(),
                $d->getUnderTimeDisplay(),
                $d->getStatus(),
            ];

            $i++;
            fputcsv($file, $arr_data);
        }


        fclose($file);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;

    }

    public function printAction($id = null, $date_from = null, $date_to = null, $department = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location = null)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisReportBundle:Attendance:print.html.twig";

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

        //getOutputData
        $data = $this->getOutputData($id, $date_from, $date_to, $department, $position, $rank, $gender, $days, $late, $late_to, $undertime, $undertime_to, $status, $location);

        $params['emp'] = null;
        $params['dept'] = null;

        //data for filter display
        $params['filter_department'] = $department == 'null' || $department == null ? 'all':$em->getRepository('HrisAdminBundle:Department')->find($department)->getName().' Department';
        $params['filter_employee'] = $id == 'null' || $id == null ? 'all':$em->getRepository('HrisWorkforceBundle:Employee')->find($id)->getDisplayName();
        $params['filter_position'] = $position == 'null' || $position == null ? 'all':$em->getRepository('HrisAdminBundle:JobTitle')->find($position)->getName();
        $params['filter_rank'] = $rank == 'null' || $rank == null ? 'all':$em->getRepository('HrisAdminBundle:JobLevel')->find($rank)->getName();
        $params['filter_gender'] = $gender == 'null' || $gender == null ? 'all':$gender;
        $params['filter_late_from'] = "";
        $params['filter_late_to'] = "";
        $params['filter_undertime_from'] = "";
        $params['filter_undertime_to'] = "";
        $params['filter_location'] = $location == 'null' || $location == null ? 'all':$em->getRepository('HrisAdminBundle:Location')->find($location)->getName();
        $params['filter_status'] = $status == 'null' || $status == null ? 'all':$status;

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

    //FOR REPORT

    public function gridAttendancesReportAction($id = null, $department = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterAttendanceGrid2($id,$department,$date_from,$date_to, $position, $rank, $gender, $days, $late, $late_to, $undertime, $undertime_to, $status, $location));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterAttendanceGrid2($id = null, $department = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $late_to = null, $undertime = null, $undertime_to = null, $status = null, $location)
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

        if ($id != null and $id != 'null')
        {
            $qry[] = "(o.employee = '".$id."')";
        }

        if ($location != null and $location != 'null')
        {
            $qry[] = "(emp.location = '".$location."')";
        }

        if ($gender != 'null')
        {
            if ($gender != null)
            {
                $qry[] = "(emp.gender IN (".$gender."))";
            }
        }

        if ($rank != 'null')
        {
            if ($rank != null)
            {
                $qry[] = "(emp.job_level = ".$rank.")";
            }
        }

        if ($position != 'null')
        {
            if ($position != null)
            {
                $qry[] = "(emp.job_title = ".$position.")";
            }
        }

        if ($status != 'null')
        {
            if ($status != null)
            {
                $qry[] = "(o.status = ".$status.")";
            }
        }

        if ($late != '0')
        {
            if ($late != 0)
            {
                $qry[] = "(o.late >= ".$late." AND o.late <= ".$late_to.")";
            }
        }

        if ($undertime != '0')
        {
            if ($undertime != 0)
            {
                $qry[] = "(o.undertime >= ".$undertime." AND o.undertime <= ".$undertime_to.")";
            }
        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    public function gridAttendanceReportAction($id = null, $date_from = null, $date_to = null, $position = null, $rank = null, $gender = null, $days = null, $late = null, $undertime = null, $status = null)
    {

    }
}