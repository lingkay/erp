<?php

namespace Hris\ReportBundle\Controller;

use Catalyst\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Catalyst\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Catalyst\NotificationBundle\Model\NotificationEvent;
use Catalyst\NotificationBundle\Entity\Notification;
use Catalyst\CoreBundle\Template\Controller\TrackCreate;
use DateTime;
use SplFileObject;
use LimitIterator;

class TotalExpenseReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_total_expense';
		$this->title = 'Total Expense';

		$this->list_title = 'Total Expense Report';
		$this->list_type = 'static';
        $this->repo = "HrisPayrollBundle:PayPayroll";
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_total_expense_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:TotalExpense:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $timestamp = strtotime("now"); //1072915200
        $params['curr_month'] = idate('m',$timestamp);
        $params['curr_year'] = idate('Y',$timestamp);

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
            'HrisReportBundle:TotalExpense:action.html.twig',
            $params
        );
    }

    public function viewAction()
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_regulars_view');

        $twig_file = 'HrisReportBundle:TotalExpense:view.html.twig';

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
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newJoin('employee','employee','getEmployee'),
            $grid->newJoin('period', 'payroll_period', 'getPayrollPeriod'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('catalyst_grid');
        return array(
            $grid->newColumn('Employee Name','getDisplayName', 'last_name', 'employee'),
            $grid->newColumn('Amount','getID','total_amount','o',array($this,'addContributions')),
        );
    }

    public function addContributions($id, $mode = null)
    {
        $em = $this->getDoctrine()->getManager();

        //get PAYROLL
        $pay_obj = $em->getRepository('HrisPayrollBundle:PayPayroll')->find($id);

        //get EMPLOYEE
        $emp_obj = $em->getRepository('HrisWorkforceBundle:Employee')->find($pay_obj->getEmployee()->getID());

        //get SSS
        $query = "SELECT sss FROM HrisPayrollBundle:PaySSSRate sss WHERE (sss.min_amount <= :salary AND sss.max_amount >= :salary)";
        $sss = $em->createQuery($query)
            ->setParameter('salary', $emp_obj->getMonthlyRate())
            ->getSingleResult();

        //get PHILHEALTH
        $query = "SELECT ph FROM HrisPayrollBundle:PayPhilHealthRate ph WHERE (ph.min_amount <= :salary AND ph.max_amount >= :salary)";

        $ph = $em->createQuery($query)
            ->setParameter('salary', $emp_obj->getMonthlyRate())
            ->getSingleResult();

        if ($mode == null) 
        {
            return ''.($pay_obj->getTotal() + $sss->getEmployeeContribution() + $ph->getEmployee());
        }
        else
        {
            return $pay_obj->getTotal() + $sss->getEmployeeContribution() + $ph->getEmployee();
        }
    }

    protected function padFormParams(&$params, $object = NULL)
    {
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
        $params['month_opts'] = array('01'=>'January', '02'=>'February', '03'=>'March', '04'=>'April', '05'=>'May', '06'=>'June', '07'=>'July', '08'=>'August', '09'=>'September', '10'=>'October', '11'=>'November', '12'=>'December');
        $params['year_opts'] = array('2015'=>'2015','2016'=>'2016','2017'=>'2017','2018'=>'2018','2019'=>'2019');

        return $params;
    }

    protected function hookPostSave($obj, $is_new = false)
    {

    }

    public function headers()
    {
        $headers = [
            'Employee Name',
            'Amount',            
        ];
        return $headers;
    }

    public function csvAction($month = null, $year = null)
    {
        if ($month == 'null' || $month == '') 
        {
            $timestamp = strtotime("now");
            $month = idate('m',$timestamp);
            $year = idate('Y',$timestamp);
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em    ->createQueryBuilder();
        $query          ->from('HrisPayrollBundle:PayPayroll', 'p');
        $query          ->join('HrisPayrollBundle:PayPayrollPeriod','pp','WITH','p.payroll_period=pp.id');
        $query          ->where('pp.fs_month = '.$month.' AND pp.fs_year = '.$year);
        $data = $query  ->select('p')
                        ->getQuery()
                        ->getResult();   

        $data_array = array();
        $amount = 0;
        foreach ($data as $entry) 
        {
            //$amount = $this->addContributions($entry->getPayrollPeriod()->getID(), 'X');
            //get EMPLOYEE
            $emp_obj = $em->getRepository('HrisWorkforceBundle:Employee')->find($entry->getEmployee()->getID());

            //get SSS
            $query = "SELECT sss FROM HrisPayrollBundle:PaySSSRate sss WHERE (sss.min_amount <= :salary AND sss.max_amount >= :salary)";
            $sss = $em->createQuery($query)
                ->setParameter('salary', $emp_obj->getMonthlyRate())
                ->getSingleResult();

            //get PHILHEALTH
            $query = "SELECT ph FROM HrisPayrollBundle:PayPhilHealthRate ph WHERE (ph.min_amount <= :salary AND ph.max_amount >= :salary)";

            $ph = $em->createQuery($query)
                ->setParameter('salary', $emp_obj->getMonthlyRate())
                ->getSingleResult();

             array_push($data_array, array(
                    "name" => $entry->getEmployee()->getDisplayName(), 
                    "amount" => $entry->getTotal() + $sss->getEmployeeContribution() + $ph->getEmployee(),
                ));
        }

        $date = new DateTime();
        $filename = 'Total Expense Report ('.$month.'/'.$year.').csv';

        $file = fopen('php://output', 'w');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

         $i=0;
        foreach ($data_array as $d)
        {
            $arr_data = [
                $d['name'],
                $d['amount'],

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

    public function printAction($month = null, $year = null)
    {
        if ($month == 'null' || $month == '') 
        {
            $timestamp = strtotime("now"); //1072915200
            $month = idate('m',$timestamp);
            $year = idate('Y',$timestamp);
        }

        $settings = $this->get('hris_settings');
        $wf = $this->get('hris_workforce');
        $em = $this->getDoctrine()->getManager();
        $twig = "HrisReportBundle:TotalExpense:print.html.twig";

        $conf = $this->get('catalyst_configuration');
        $media = $this->get('catalyst_media');
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

        $query = $em    ->createQueryBuilder();
        $query          ->from('HrisPayrollBundle:PayPayroll', 'p');
        $query          ->join('HrisPayrollBundle:PayPayrollPeriod','pp','WITH','p.payroll_period=pp.id');
        $query          ->where('pp.fs_month = '.$month.' AND pp.fs_year = '.$year);
        $data = $query  ->select('p')
                        ->getQuery()
                        ->getResult();   

        $data_array = array();
        $amount = 0;
        foreach ($data as $entry) 
        {
            //$amount = $this->addContributions($entry->getPayrollPeriod()->getID(), 'X');
            //get EMPLOYEE
            $emp_obj = $em->getRepository('HrisWorkforceBundle:Employee')->find($entry->getEmployee()->getID());

            //get SSS
            $query = "SELECT sss FROM HrisPayrollBundle:PaySSSRate sss WHERE (sss.min_amount <= :salary AND sss.max_amount >= :salary)";
            $sss = $em->createQuery($query)
                ->setParameter('salary', $emp_obj->getMonthlyRate())
                ->getSingleResult();

            //get PHILHEALTH
            $query = "SELECT ph FROM HrisPayrollBundle:PayPhilHealthRate ph WHERE (ph.min_amount <= :salary AND ph.max_amount >= :salary)";

            $ph = $em->createQuery($query)
                ->setParameter('salary', $emp_obj->getMonthlyRate())
                ->getSingleResult();

             array_push($data_array, array(
                    "name" => $entry->getEmployee()->getDisplayName(), 
                    "amount" => $entry->getTotal() + $sss->getEmployeeContribution() + $ph->getEmployee(),
                ));
        }

        $params['month'] = $monthName = date('F', mktime(0, 0, 0, $month, 10));;
        $params['year'] = $year;
        
        $params['all'] = $data_array;
        $pdf = $this->get('catalyst_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function gridTotalExpenseReportAction($month = null, $year = null)
    {
        $this->hookPreAction();

        $gloader = $this->setupGridLoader();

        $gloader->setQBFilterGroup($this->filterTotalExpenseGrid($month, $year));
        $gres = $gloader->load();
        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function filterTotalExpenseGrid($month = null, $year = null)
    {
        $grid = $this->get('catalyst_grid');
        $fg = $grid->newFilterGroup();


        
        if ($month == null || $month == 'null') 
        {
            $timestamp = strtotime("now"); //1072915200
            $month = idate('m',$timestamp);
            $year = idate('Y',$timestamp);
            $qry[] = "(period.fs_month = ".$month." AND period.fs_year = ".$year.")";
        }
        else
        {
            $qry[] = "(period.fs_month = ".$month." AND period.fs_year = ".$year.")";
        }

        

        

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
        }

        return $fg->where($filter);
    }

    public function gridRegularReportAction($id = null)
    {
        $resp = new Response(json_encode(1));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function updateTotalAction($month = null, $year = null)
    {
        $em = $this->getDoctrine()->getManager();
        //get SSS
        $query = "SELECT p FROM HrisPayrollBundle:PayPayroll p LEFT JOIN HrisPayrollBundle:PayPayrollPeriod x WITH p.payroll_period = x.id WHERE (x.fs_month = :month AND x.fs_year = :year) AND p.payroll_period is not null";
        $ppp = $em->createQuery($query)
            ->setParameter('month', $month)
            ->setParameter('year', $year)
            ->getResult();

        $total = 0;
        $count = 0;
            foreach ($ppp as $payperiod) 
            {
                $amount = $this->addContributions($payperiod->getPayrollPeriod()->getID(), 'X');
                $total = $total + $amount;
                $count++;
            }



        $data = array('amount'=>number_format($total, 2), 'count'=>number_format($count));
        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
}