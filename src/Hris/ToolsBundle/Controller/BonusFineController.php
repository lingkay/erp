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
use Hris\ToolsBundle\Entity\EmployeeBonusFine;
use DateTime;
use SplFileObject;
use LimitIterator;

class BonusFineController extends CrudController
{
	use TrackCreate;
    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'hris_tools_bonus';
        $this->title = 'Bonus/Fines';
        $this->list_title = 'Bonus/Fines';
        $this->list_type = 'dynamic';
        $this->repo = "HrisToolsBundle:EmployeeBonusFine";
    }


    protected function newBaseClass()
    {
        return new EmployeeBonusFine();
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
            $grid->newJoin('a', 'team', 'getTeam')
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee', 'getEmployeeName', 'employee'),
            $grid->newColumn('Team', 'getName', 'team', 'a'),
            $grid->newColumn('Given By', 'getGivenName', 'name','g'),
            // $grid->newColumn('Roles', 'getGroupsText', 'id', 'o', null, false),
            $grid->newColumn('Bonus/Fine', 'getBFType', 'bf_type'),
            $grid->newColumn('Reason', 'getReasonName', 'bf_type'),
            $grid->newColumn('Amount', 'getAmount', 'debit'),
 
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
	    $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');


        $params['bonus_opts'] = $sm->getBonusOptions();
        $params['fine_opts'] = $sm->getFineOptions();
        $params['type_opts'] = [EmployeeBonusFine::TYPE_SALARY => EmployeeBonusFine::TYPE_SALARY,
        						EmployeeBonusFine::TYPE_CASH => EmployeeBonusFine::TYPE_CASH];
        $params['bftype_opts'] = [EmployeeBonusFine::BFTYPE_BONUS => EmployeeBonusFine::BFTYPE_BONUS,
        						EmployeeBonusFine::BFTYPE_FINE => EmployeeBonusFine::BFTYPE_FINE];	

        $params['emp_opts'] = $um->getUserFullNameOptions();
	    $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');


        $employee = $um->findUser($data['employee']);
        $given_by = $um->findUser($data['given']);
        
    
        $o->setEmployee($employee)
            ->setTeam($employee->getArea())
            ->setGivenBy($given_by)
            ->setType($data['type'])
            ->setBFType($data['bf_type'])
            ->setDateReleased(new DateTime($data['date_released']))
            ->setCutoff($data['cutoff']);

        switch($o->getBFType()){
            case EmployeeBonusFine::BFTYPE_BONUS:
                $o->setDebit($data['amount']);
                $o->setCredit(0);
                $reason = $sm->findBonus($data['reason_bonus']);
                break;
            case EmployeeBonusFine::BFTYPE_BONUS:
                $o->setCredit($data['amount']);
                $o->setDebit(0);
                $reason = $sm->findFine($data['reason_fine']);
                break;
        }
        
        $o->setReason($reason);

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
                "Bonus/Fine",
                "Given By",
                "Salary/Cash",
                "Reason",
                "Amount",
                "Date of B/F",
                "Date of Encoding",
                "Given",
                "Month",
                "Cutoff",
                "Cutoff Year"];
    }

    protected function getData()
    {
        $em = $this->getDoctrine()->getManager();
      
        $qb = $em->createQueryBuilder();
        $qb->select('o')
            ->from('HrisToolsBundle:EmployeeBonusFine', 'o')
            ->where('o.date_create between :date_from and :date_to ')
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
        $filename = 'BONUSFINE_'.$date.'.csv';
        $file = fopen('php://output', 'w');
        ob_start();

        fputcsv($file, $csv_headers);
        foreach ($this->getData() as $row) {
           $data = [];
           $data[] = $row->getEmployee()->getID();
           $data[] = $row->getEmployeeName();
           $data[] = $row->getTeam()->getName();
           $data[] = $row->getBFType();
           $data[] = $row->getGivenNAme();
           $data[] = $row->getType();
           $data[] = $row->getReasonName();
           $data[] = $row->getAmount();
           $data[] = $row->getDateReleased()->format('m/d/Y');
           $data[] = $row->getDateCreate()->format('m/d/Y');
           $data[] = $row->isGiven()?"Y":"N";
           $data[] = $row->getDateReleased()->format('F');
           $data[] = $row->getCutoff();
           $data[] = $row->getDateReleased()->format('Y');

           fputcsv($file, $data);
        
        }
        fclose($file);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);

        return $response;
    }




}