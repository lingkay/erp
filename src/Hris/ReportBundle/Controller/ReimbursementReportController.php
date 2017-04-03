<?php

namespace Hris\ReportBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Reimbursement;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use DateTime;

class ReimbursementReportController extends CrudController
{
    use TrackCreate;
	public function __construct()
	{
		$this->route_prefix = 'hris_report_reimbursement';
		$this->title = 'Reimbursement Report';

		$this->list_title = 'Reimbursement Report';
		$this->list_type = 'static';
		$this->repo = 'HrisWorkforceBundle:Reimbursement';
	}

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $settings = $this->get('hris_settings');
        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List', 'hris_report_reimbursement_index');

        $date_from = new DateTime();
        $date_to = new DateTime();
        $date_from->format("Y-m-d");
        $date_to->format("Y-m-d");

        $this->padFormParams($params, $date_from, $date_to);
        $twig_file = 'HrisReportBundle:Reimbursement:index.html.twig';

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
            $grid->newColumn('Code', 'getCode', 'code'),
            $grid->newColumn('Date Filed', 'getDateFiled', 'date_filed','o',array($this,'formatDate')),
            $grid->newColumn('Date Approved', 'getDateApproved', 'date_approved','o',array($this,'formatDate')),
            $grid->newColumn('Employee Name', 'getDisplayName', 'last_name', 'emp'),
            $grid->newColumn('Expense Type', 'getExpense', 'expense_type'),
            $grid->newColumn('Status', 'getDisplayStatus', 'status'),
            $grid->newColumn('Cost(Php)', 'getCost', 'cost','o',array($this,'formatPrice')),
        );
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
        $params['status_opts'] = [
        	Reimbursement::STATUS_APPROVED => Reimbursement::STATUS_APPROVED,
        	Reimbursement::STATUS_REJECT => Reimbursement::STATUS_REJECT,
        ];
        $params['interval'] = 'daily';

        return $params;
    }


    protected function hookPostSave($obj, $is_new = false)
    {      

    }

    protected function headers()
    {
        $headers = [
            'Code',
            'Date Filed',
            'Date Approved',
            'Employee Name',
            'Expense Type',
            'Status',
            'Cost(Php)',
        ];

        return $headers;
    }
    public function printAction($department = null, $date_from = null, $date_to = null, $status = null)
    {
        $em = $this->getDoctrine()->getManager();
        $setting = $this->get('hris_settings');
        $twig = "HrisReportBundle:Reimbursement:print.html.twig";

        $dfrom = new DateTime($date_from."T00:00:00");
        $dto = new DateTime($date_to."T23:59:59");

        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');
    
        $params['grid_cols'] = $this->headers();
        $dept = '';
        $dept_name = '';
        $title = [];
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
        $query->from('HrisWorkforceBundle:Reimbursement', 'o')
              ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee = e.id')
              ->where('o.date_filed <= :date_to')
              ->andWhere('o.date_filed >= :date_from')
              ->setParameter('date_from', $dfrom->format('Y-m-d H:i:s'))
              ->setParameter('date_to', $dto->format('Y-m-d H:i:s'));

        if($status != 'null' AND $status != null)
        {
            $query->andWhere("o.status = '".$status."'");
            $title[] = $status;
        }
        else
        {
            $query->andWhere("o.status != '".Reimbursement::STATUS_PENDING."'");   
        }

        $title[] = "Reimbursement(s)";

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
        $total = 0;

        foreach ($data as $key => $value) {
            $total = $value->getCost() + $total;
        }


        $title[] = "from ".$dfrom->format('F d Y')." to ".$dto->format('F d Y')."";

        $head_title = implode(" ", $title);
        $params['total'] = floatval($total);
        $params['data'] = $data;
        $params['status'] = $status;
        $params['date_from'] = $dfrom->format('F m Y');
        $params['date_to'] = $dto->format('F m Y');
        $params['list_title'] = $head_title;
        $params['data'] = $data;

        $pdf = $this->get('gist_pdf');
        $pdf->newPdf('A4');
        $html = $this->render($twig, $params);
        return $pdf->printPdf($html->getContent());
    }
    public function gridReimbursementAction($department = null, $date_from = null, $date_to = null, $status = null)
    {
        $gl = $this->setupGridLoader();
        $qry = array();
        $dfrom = $date_from=='null'? new DateTime():new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime():new DateTime($date_to.'23:59:59');

        $grid = $this->get('gist_grid');
        $fg = $grid->newFilterGroup();
        
        $qry[] = "(o.date_filed <= '".$dto->format('Y-m-d')."' AND o.date_filed >= '".$dfrom->format('Y-m-d')."')";
	    
		if($department != 'null')
		{
			$qry[] = "(o.employee IN (SELECT e.id FROM HrisWorkforceBundle:Employee e WHERE e.department = '".$department."'))";
		}   

		if($status != 'null')
		{
			$qry[] = "(o.status = '".$status."')";
		}
        else
        {
            $qry[] = "(o.status != '".Reimbursement::STATUS_PENDING."')"; 
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

    public function reimbursementCSVAction($department = null, $date_from = null, $date_to = null, $status = null)
    {
        $em = $this->getDoctrine()->getManager();
        $date = new DateTime;
        $filename = $this->title.$date->format('Ymd').'.csv';

        $dfrom = $date_from=='null'? new DateTime():new DateTime($date_from.'00:00:00');
        $dto = $date_to=='null'? new DateTime():new DateTime($date_to.'23:59:59');

        $query = $em->createQueryBuilder();
        $query->from('HrisWorkforceBundle:Reimbursement', 'o')
              ->join('HrisWorkforceBundle:Employee','e','WITH','o.employee = e.id')
              ->where('o.date_filed <= :date_to')
              ->andWhere('o.date_filed >= :date_from')
              ->setParameter('date_from', $dfrom->format('Y-m-d'))
              ->setParameter('date_to', $dto->format('Y-m-d'));

        if($status != 'null' AND $status != null)
        {
            $query->andWhere("o.status = '".$status."'");
        }
        else
        {
            $query->andWhere("o.status != '".Reimbursement::STATUS_PENDING."'");   
        }

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
            $date = '';
            if($dt->getDateApproved() != null)
            {
                $date = $dt->getDateApproved()->format('m/d/Y');
            }
            $array = [
                $dt->getCode(),
                $dt->getDateFiled()->format('m/d/Y'),
                $date,
                $dt->getEmployee()->getDisplayName(),
                $dt->getExpense(),
                $dt->getStatus(),
                number_format($dt->getCost(),2,'.',',')
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