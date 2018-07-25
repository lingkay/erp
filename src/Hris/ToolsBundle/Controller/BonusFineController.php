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
        return $obj->getUsername();
    }

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('a', 'area', 'getArea'),
    //         $grid->newJoin('g', 'group', 'getGroup'),
    //     );
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Employee', 'getEmployeeName', 'last_name'),
            $grid->newColumn('Team', 'getTeam', 'last_name'),
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
    }




}