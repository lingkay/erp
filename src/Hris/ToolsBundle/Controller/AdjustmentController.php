<?php

namespace Hris\ToolsBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Utility\ManagerGroupName;
use Hris\ToolsBundle\Entity\Schedule;
use Hris\ToolsBundle\Entity\ScheduleEntry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Hris\WorkforceBundle\Entity\Attendance;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\ToolsBundle\Entity\EmployeeAdjustment;
use DateTime;
use SplFileObject;
use LimitIterator;

class AdjustmentController extends CrudController
{
    use TrackCreate;
    protected $date_from;
    protected $date_to;

    public function __construct()
    {
        $this->route_prefix = 'hris_tools_adjustment';
        $this->title = 'Adjustment';
        $this->list_title = 'Adjustment';
        $this->list_type = 'dynamic';
        $this->repo = "HrisToolsBundle:EmployeeAdjustment";
    }


    protected function newBaseClass()
    {
        return new EmployeeAdjustment();
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
            $grid->newColumn('Adjustment', 'getAdjustmentType', 'adjustment_type'),
            $grid->newColumn('Amount(Php)', 'getAmount', 'debit', 'o', [$this, 'formatPrice']),
 
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
	    $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');

        $params['adjustment_opts'] = [EmployeeAdjustment::ADJUSTMENT_FINE => EmployeeAdjustment::ADJUSTMENT_FINE,
                                        EmployeeAdjustment::ADJUSTMENT_BASIC => EmployeeAdjustment::ADJUSTMENT_BASIC,
                                        EmployeeAdjustment::ADJUSTMENT_13TH => EmployeeAdjustment::ADJUSTMENT_13TH,
                                        EmployeeAdjustment::ADJUSTMENT_OVERPAID => EmployeeAdjustment::ADJUSTMENT_OVERPAID];
       
        $params['type_opts'] = [EmployeeAdjustment::TYPE_ADD => EmployeeAdjustment::TYPE_ADD,
        						EmployeeAdjustment::TYPE_DEDUCTION => EmployeeAdjustment::TYPE_DEDUCTION];
        $params['emp_opts'] = $um->getUserFullNameOptions();
        $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $sm = $this->get('hris_settings');
        $um = $this->get('gist_user');


        $employee = $um->findUser($data['employee']);

        $o->setEmployee($employee)
            ->setTeam($employee->getArea())
            ->setType($data['type'])
            ->setAdjustmentType($data['adjustment_type'])
            ->setNotes($data['notes'])
            ->setDateAdjustment(new DateTime($data['date_adjustment']))
            ->setCutoff($data['cutoff']);

        switch($o->getType()){
            case EmployeeAdjustment::TYPE_ADD:
                $o->setDebit($data['amount']);
                $o->setCredit(0);
                break;
            case EmployeeAdjustment::TYPE_DEDUCTION:
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




}