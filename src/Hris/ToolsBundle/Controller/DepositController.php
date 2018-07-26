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
            $grid->newColumn('Reason', 'getNotes', 'notes'),
            $grid->newColumn('Amount', 'getAmount', 'debit'),
 
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




}