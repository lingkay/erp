<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Gist\ValidationException;
use Gist\NotificationBundle\Model\NotificationEvent;
use Gist\NotificationBundle\Entity\Notification;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\AccountingBundle\Entity\ChartOfAccount;
use DateTime;
use SplFileObject;
use LimitIterator;

class ChartOfAccountController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_accounts';
        $this->title = 'Chart of Accounts';
        $this->list_title = 'Chart of Accounts';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:ChartOfAccount";
    }


    protected function newBaseClass()
    {
        return new ChartOfAccount();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('a', 'team', 'getTeam'),
    //         // $grid->newJoin('g', 'group', 'getGroup'),
    //     );
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Account Name', 'getName', 'name'),
            $grid->newColumn('Code', 'getCode', 'code'),
            $grid->newColumn('Description', 'getNotes', 'notes'),
 
        );
    }

    // protected function padFormParams(&$params, $user = null)
    // {
	    
    //     $sm = $this->get('hris_settings');
    //     $um = $this->get('gist_user');

    //     $params['deposit_opts'] = $sm->getDepositOptions();
       
    //     $params['type_opts'] = [EmployeeDeposit::TYPE_RETURN => EmployeeDeposit::TYPE_RETURN,
    //     						EmployeeDeposit::TYPE_DEDUCTION => EmployeeDeposit::TYPE_DEDUCTION];
    //     $params['emp_opts'] = $um->getUserFullNameOptions();
    //     $params['cutoff_opts'] = ["A"=>"A", "B"=>"B"];
    // }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();


        $o->setName($data['name'])
            ->setCode($data['code'])
            ->setNotes($data['notes']);

        $this->updateTrackCreate($o, $data, $is_new);
    }




}