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
use Gist\AccountingBundle\Entity\MainAccount;
use DateTime;
use SplFileObject;
use LimitIterator;

class MainAccountController extends CrudController
{
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_main';
        $this->title = 'Main Accounts';
        $this->list_title = 'Main Accounts';
        $this->list_type = 'dynamic';
        $this->repo = "GistAccountingBundle:MainAccount";
    }


    protected function newBaseClass()
    {
        return new MainAccount();
    }
    
    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getCode();
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Account Name', 'getName', 'name'),
            $grid->newColumn('Code', 'getCode', 'code'),
            // $grid->newColumn('Description', 'getNotes', 'notes'),
 
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();


        $o->setName($data['name'])
            ->setCode($data['code']);
            // ->setNotes($data['notes']);

        $this->updateTrackCreate($o, $data, $is_new);
    }




}