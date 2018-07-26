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
            $grid->newColumn('Amount', 'getAmount', 'debit'),
 
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




}