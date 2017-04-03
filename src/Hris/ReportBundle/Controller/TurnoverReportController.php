<?php

namespace Hris\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Resign;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;

class TurnoverReportController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_report_turnover';
        $this->title = 'Turnover Report';

        $this->list_title = 'Turnover Report';
        $this->list_type = 'static';
        $this->repo = 'HrisWorkforceBundle:Resign';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_turnover_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:Turnover:index.html.twig';

        $params['date_from'] = $date_from;
        $params['date_to'] = $date_to;

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

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
            $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed','o',array($this,'formatDate')),
            $grid->newColumn('Date Approved', 'getDateApproved', 'date_approved','o',array($this,'formatDate')),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'emp'),
            $grid->newColumn('Department','getDepartment','department','emp',array($this,'getName')),
            $grid->newColumn('Position','getJobTitle','job_title','emp',array($this,'getName')),
            $grid->newColumn('Supervisor','getSupervisor','supervisor','emp',array($this,'getDisplayName')),
        );
    }

    public function getName($obj)
    {
        return $obj->getName();
    }

    public function getDisplayName($obj)
    {
        if($obj == null)
        {
            return '';
        }
        else
        {
            return $obj->getDisplayName();
        }
    }

    protected function padFormParams(&$params, $object = NULL){
        $em = $this->getDoctrine()->getManager();
        $settings = $this->get('hris_settings');

        $params['dept_opts'] = $settings->getDepartmentOptions();
        $int_opts = [
            'daily' => 'Daily',
            'weekly' => 'Weekly',
            'monthly' => 'Monthly'
        ];
        $params['interval'] = 'daily';

        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {
        //check for tardiness >= 5 for the current month
    }

    public function gridTurnoverAction($department = null, $date_from = null, $date_to = null)
    {
        $gl = $this->setupGridLoader();
        $qry = array();
        $date = new DateTime;
        $dfrom = $date_from=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_to.'23:59:59');

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        
        $qry[] = "(o.status = '".Resign::STATUS_ACCEPT."')";
        $qry[] = "(o.date_approved <= '".$dto->format('Y-m-d H:i:s')."' AND o.date_approved >= '".$dfrom->format('Y-m-d H:i:s')."')";
        
        if($department != 'null')
        {
            $qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$department."'))";
        }   

        if (!empty($qry))
        {
            $filter = implode(' AND ', $qry);
            $fg->where($filter);
            $gl->setQBFilterGroup($fg);
        }
        

        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    protected function headers()
    {
        $headers = [
            'Date Filed',
            'Date Approved',
            'Employee Name',
            'Department',
            'Position',
            'Supervisor',
        ];

        return $headers;
    }

    public function printAction($department = null, $date_from = null, $date_to = null)
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $this->get('hris_settings');
        $twig = "HrisReportBundle:Turnover:print.html.twig";

        $date = new DateTime;
        $dfrom = $date_from=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_to.'23:59:59');

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');

        $params['grid_cols'] = $this->headers();
        $dept = '';
        $dept_name = '';
        $title = [];
        $title[] = "Employee Resgination";
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

        $query = $em->createQueryBuilder();
        $query->from('HrisWorkforceBundle:Resign', 'o')
              ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee = e.id')
              ->where('o.date_approved <= :date_to')
              ->andWhere('o.date_approved >= :date_from')
              ->setParameter('date_from', $dfrom->format('Y-m-d H:i:s'))
              ->setParameter('date_to', $dto->format('Y-m-d H:i:s'));
        if($department != 'null' AND $department != null)
        {
            $query->andWhere("e.department = '".$department."'");
            $dept = $setting->getDepartment($department);
        }

        $data = $query->select('o')
                      ->getQuery()
                      ->getResult();

        if($dept != '')
        {
            $dept_name = $dept->getName();
            $title[] = "in ".$dept_name." Department ";
        }

        $title[] = "from ".$dfrom->format('F d Y')." to ".$dto->format('F d Y')."";

        $head_title = implode(" ", $title);
        $params['department_name'] = $dept_name;
        $params['date_from'] = $dfrom->format('F m Y');
        $params['date_to'] = $dto->format('F m Y');
        $params['list_title'] = $head_title;
        $params['data'] = $data;

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }

    public function turnoverCSVAction($department = null, $date_from = null, $date_to = null)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new DateTime;

        $filename = $this->title.$date->format('Ymd').'.csv';
        
        $dfrom = $date_from=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime($date->format('Y-m-d H:i:s')):new DateTime($date_to.'23:59:59');

        $query = $em->createQueryBuilder();
        $query->from('HrisWorkforceBundle:Resign', 'o')
              ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee = e.id')
              ->where('o.date_approved <= :date_to')
              ->andWhere('o.date_approved >= :date_from')
              ->setParameter('date_from', $dfrom->format('Y-m-d H:i:s'))
              ->setParameter('date_to', $dto->format('Y-m-d H:i:s'));

        if($department != 'null' AND $department != null)
        {
            $query->andWhere("e.department = '".$department."'");
        }

        $data = $query->select('o')
                      ->getQuery()
                      ->getResult();

        // redirect file to stdout, store in output buffer and place in $csv
        $file = fopen('php://output', 'p');
        ob_start();

        $csv_headers = $this->headers();

        fputcsv($file, $csv_headers);

        foreach ($data as $dt)
        {   
            $supervisor = '';
            if($dt->getEmployee()->getSupervisor() != null)
            {
                $supervisor = $dt->getEmployee()->getSupervisor()->getDisplayName();
            }
            $array = [
                $dt->getDateFiled()->format('m/d/Y'),
                $dt->getDateApproved()->format('m/d/Y'),
                $dt->getEmployee()->getDisplayName(),
                $dt->getEmployee()->getDepartment()->getName(),
                $dt->getEmployee()->getJobTitle()->getName(),
                $supervisor,
            ];
            fputcsv($file, $array);
        }

        fclose($file);
        $csv = ob_get_contents();
        ob_end_clean();

        $response = new Response(); 
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->setContent($csv);

        return $response;
    }
}