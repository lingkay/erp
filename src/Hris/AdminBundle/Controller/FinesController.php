<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Hris\AdminBundle\Entity\Fines;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\TaxMatrix;
use DateTime;

class FinesController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_payroll_settings_fines';
        $this->title = 'Fines';
        $this->list_title = 'Fines';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Fines();
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setAmount($data['amount']);
        $o->setFormula($data['formula']);

        $em = $this->getDoctrine()->getManager();
        $type = $em->getRepository('HrisAdminBundle:FineTypes')->findOneById($data['type']);
        $o->setType($type);

        $vtype = $em->getRepository('HrisAdminBundle:FineValueTypes')->findOneById($data['value_type']);
        $o->setValueType($vtype);
    }

    protected function padFormParams(&$params, $o = null)
    {
        $settings = $this->get('hris_settings');
        $params['type_options'] = $this->getTypeOptions();
        $params['value_type_opts'] = $settings->getFinesValueTypeOptions();
        return $params;
    }

    public function getTypeOptions($filter = array())
    {
        $em = $this->getDoctrine()->getManager();
        $whs = $em
            ->getRepository('HrisAdminBundle:FineTypes')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $bonusTypeOptions = array();
        foreach ($whs as $wh)
            $bonusTypeOptions[$wh->getID()] = $wh->getName();

        return $bonusTypeOptions;
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

        return $this->render('HrisAdminBundle:Fines:form.html.twig', $params);
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
