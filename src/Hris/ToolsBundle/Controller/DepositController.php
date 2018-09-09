<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Utility\ManagerGroupName;
use Hris\ToolsBundle\Entity\Schedule;
use Hris\ToolsBundle\Entity\ScheduleEntry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\TemplateBundle\Model\BaseController as Controller;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\ToolsBundle\Entity\EmployeeDeposit;
use DateTime;
use SplFileObject;
use LimitIterator;

class DepositController extends CrudController
{
    use TrackCreate;
    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'hris_tools_deposit';
        $this->title = 'Deposit';
        $this->list_title = 'Deposit';
        $this->list_type = 'dynamic';
        $this->repo = "HrisToolsBundle:EmployeeDeposit";
    }


    protected function newBaseClass()
    {
        return new EmployeeDeposit();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getID();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'team', 'getTeam'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee', 'getEmployeeName', 'employee'),
            $grid->newColumn('Team', 'getName', 'name', 'a'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Record Date', 'getDateCreate', 'date_create','o', [$this,'formatDate']),
          
            $grid->newColumn('Reason', 'getNotes', 'notes'),
            $grid->newColumn('Amount', 'getAmount', 'debit', 'o', [$this, 'formatPrice']),
 
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
	    
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');

        $params['deposit_opts'] = $sm->getDepositOptions();
       
        $params['type_opts'] = [EmployeeDeposit::TYPE_RETURN => EmployeeDeposit::TYPE_RETURN,
        						EmployeeDeposit::TYPE_DEDUCTION => EmployeeDeposit::TYPE_DEDUCTION];
        $params['emp_opts'] = $um->getUserFullNameOptions();
        $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');


        $employee = $um->findUser($data['employee']);
        $deposit_type = $sm->findDepositType($data['deposit_type']);

        $o->setEmployee($employee)
            ->setTeam($employee->getArea())
            ->setType($data['type'])
            ->setDepositType($deposit_type)
            ->setNotes($data['notes'])
            ->setDateDeposit(new DateTime($data['date_deposit']))
            ->setCutoff($data['cutoff']);

        switch($o->getType()){
            case EmployeeDeposit::TYPE_RETURN:
                $o->setDebit($data['amount']);
                $o->setCredit(0);
                break;
            case EmployeeDeposit::TYPE_DEDUCTION:
                $o->setCredit($data['amount']);
                $o->setDebit(0);
                break;
        }

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function padListParams(&$params, $obj = null)
    {
        $params['date_from'] = $this->date_from->format('m/d/Y'); //$this->date_from->format('m/d/Y'): $date_from->format('m/d/Y');
        $params['date_to'] = $this->date_to->format('m/d/Y');// != null?$this->date_to->format('m/d/Y'): $date_to->format('m/d/Y');
        
        return $params;

    }

    protected function hookPreAction()
    {
        $this->getControllerBase();
        if($this->getRequest()->get('date_from') != null){
            $this->date_from = new DateTime($this->getRequest()->get('date_from'));
        }else {
           $date_from = new DateTime();
           $date_from->modify('first day of this month');
           $this->date_from = $date_from;
        }

        if($this->getRequest()->get('date_to') != null){
            $this->date_to = new DateTime($this->getRequest()->get('date_to'));
        }else {
           $date_to = new DateTime();
           $date_to->modify('last day of this month');
           $this->date_to = $date_to;
        }
    }


    protected function filterGrid()
    {
        $this->date_from->setTime(0,0);
        $this->date_to->setTime(23,59);

        $fg = parent::filterGrid();
        $fg->where('o.date_create between :date_from and :date_to ')
            ->setParameter("date_from", $this->date_from)
            ->setParameter("date_to", $this->date_to);
     
        return $fg;
    }

    protected function headers()
    {
        return ["Employee Code",
                "Employee",
                "Team",
                "Deduction/Return",
                "Amount",
                "Amount(-)",
                "Deposit",
                "Description",
                "Date of Deposit",
                "Date of Return",
                "Month",
                "Cutoff",
                "Cutoff Year"];
    }

    protected function getData()
    {
        $em = $this->getDoctrine()->getManager();
      
        $qb = $em->createQueryBuilder();
        $qb->select('o')
            ->from('HrisToolsBundle:EmployeeDeposit', 'o')
            ->where('o.date_deposit between :date_from and :date_to ')
            ->setParameter('date_from', $this->date_from)
            ->setParameter('date_to', $this->date_to);

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function csvAction()
    {
        $this->hookPreAction();

        $csv_headers = $this->headers();
        $date = $this->date_from->format('Ymd')."-".$this->date_to->format('Ymd');
        $filename = 'DEPOSIT_'.$date.'.csv';
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, $csv_headers);
        foreach ($this->getData() as $row) {
           $data = [];
           $data[] = $row->getEmployee()->getID();
           $data[] = $row->getEmployeeName();
           $data[] = $row->getTeam()->getName();
           $data[] = $row->getType();
           $data[] = $row->getAmount();
           $data[] = $row->getAmount();
           $data[] = $row->getDepositType()->getName();
           $data[] = $row->getNotes();
           $data[] = $row->getDateDeposit()->format('m/d/Y');
           $data[] = $row->getDateReturned()!= null ? $row->getDateReturned()->format('m/d/Y'): "";
           $data[] = $row->getDateDeposit()->format('F');
           $data[] = $row->getCutoff();
           $data[] = $row->getDateDeposit()->format('Y');

           fputcsv($file, $data);
        
        }
        fclose($file);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }



}