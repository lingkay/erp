<?php

namespace Hris\ReportBundle\Controller;

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

class RegularsReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_regulars';
		$this->title = 'Regular Employees';

		$this->list_title = 'Regular Employees Report';
		$this->list_type = 'static';
        $this->repo = "HrisWorkforceBundle:Employee";
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_regulars_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:Regulars:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

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
            'HrisReportBundle:Regulars:action.html.twig',
            $params
        );
    }

    public function viewAction()
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_regulars_view');

        $twig_file = 'HrisReportBundle:Regulars:view.html.twig';

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
        return new RegularsReport();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('department','department','getDepartment'),
            $grid->newJoin('position','job_title','getJobTitle'),
            $grid->newJoin('rank','job_level','getJobLevel'),
        );
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Employee Name','getDisplayName', 'last_name'),
            $grid->newColumn('Date Hired','getDateHiredFormatted', 'date_hired'),
            $grid->newColumn('Rank','getName','name','rank'),
            $grid->newColumn('Position','getName','name','position'),
        );
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $employee = $em->getRepository('HrisWorkforceBundle:Employee')->findAll();
        $dept_opts = array();
        $emp_opts = array(0 => '[Select Employee]');

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


        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {
        //check for tardiness >= 5 for the current month
        

    }

    public function headers()
    {
        $headers = [
            'Employee Name',
            'Date Hired',            
            'Rank',
            'Position',
        ];
        return $headers;
    }

    private function getOutputData($id)
    {
        $em = $this->getDoctrine()->getManager();
        if ($id == null || $id == '') 
        {
            $query = $em    ->createQueryBuilder();
            $query          ->from('HrisWorkforceBundle:Employee', 'e');
            $query          ->join('HrisAdminBundle:Department','d','WITH','e.department=d.id');
            $data = $query          ->select('e')
                                    ->getQuery()
                                    ->getResult();   
        }
        else
        {
            $dept_id = explode(',',$id);
            $query = $em    ->createQueryBuilder();
            $query          ->from('HrisWorkforceBundle:Employee', 'e');
            $query          ->join('HrisAdminBundle:Department','d','WITH','e.department=d.id');
            $query          ->where('d.id IN ('. implode(', ', $dept_id).')');
            $data = $query          ->select('e')
                                    ->getQuery()
                                    ->getResult(); 
        }   

        return $data;
    }

    public function csvAction($id = null)
    {
        $data = $this->getOutputData($id);

        $date = new DateTime();
        $filename = 'Regular Employees Report.csv';

        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

         $i=0;
        foreach ($data as $d)
        {
            $arr_data = [
                $d->getDisplayName(),
                $d->getDateHiredFormatted(),
                $d->getJobLevel()->getName(),
                $d->getJobTitle()->getName()

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

    public function printAction($id = null)
    {
        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisReportBundle:Regulars:print.html.twig";

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

        $data = $this->getOutputData($id);

        $params['all'] = $data;
        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function gridRegularsReportAction($id = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterAttendanceGrid3($id));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterAttendanceGrid3($id = null)
    {
        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        

        if ($id == null || $id == 'null') 
        {
            $qry[] = "(department.name LIKE '%%')";
        }
        else
        {
            $dept_id = explode(',',$id);
            $qry[] = '(department.id IN ('. implode(', ', $dept_id).'))';
        }

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    public function gridRegularReportAction($id = null)
    {
        // $em = $this->getDoctrine()->getManager();
        // $am = $this->get('hris_attendance');
        // $data = $em->getRepository('HrisWorkforceBundle:Employees')->findBy(array('employee' => $id));
        $resp = new Response(json_encode($arr));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
}