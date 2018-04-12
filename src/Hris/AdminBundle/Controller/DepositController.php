<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Hris\AdminBundle\Entity\Deposit;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\TaxMatrix;
use DateTime;

class DepositController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_settings_deposit';
        $this->title = 'Deposit';
        $this->list_title = 'Deposit';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Deposit();
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setAmount($data['amount']);
        $o->setName($data['name']);
    }

    protected function padFormParams(&$params, $o = null)
    {
        return $params;
    }

    public function editFormAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('HrisAdminBundle:Deposit:form.html.twig', $params);
    }


    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getID();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name','getName','name'),
            $grid->newColumn('Amount','getAmount','amount'),
        );
    }
}
