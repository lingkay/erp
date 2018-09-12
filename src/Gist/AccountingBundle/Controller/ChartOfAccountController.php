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

    protected function padFormParams(&$params, $user = null)
    {
	    
        $am = $this->get('gist_accounting');

        $params['main_opts'] = $am->getMainAccountOptions();

    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');

        $main = $am->findMainAccount($data['main_account']);
        $o->setName($data['name'])
            ->setCode($this->setCode($o, $main, $is_new))
            ->setNotes($data['notes']);
        $em->persist($o);

        $main->addChartOfAccount($o);
        $em->persist($main);
        
        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function setCode($o, $main, $is_new)
    {
        $em = $this->getDoctrine()->getManager();

        if($is_new){
            $last_code = (integer)$main->getLastCode();
            $last_code++;
            $main->setLastCode($last_code);
            $em->persist($main);
            $em->flush();
            return $main->getCode()."-".str_pad($last_code, 4,"0",STR_PAD_LEFT);
        }else {
            return $o->getCode();
        }
    }




}