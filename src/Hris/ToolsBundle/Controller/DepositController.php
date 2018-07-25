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
        return $obj->getUsername();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'area', 'getArea'),
            // $grid->newJoin('g', 'group', 'getGroup'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee', 'getEmployeeName', 'last_name'),
            $grid->newColumn('Team', 'getName', 'name', 'a'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Reason', 'getNotes', 'notes'),
            $grid->newColumn('Amount', 'getAmount', 'debit'),
 
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
	    $em = $this->getDoctrine()->getManager();
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
    }




}