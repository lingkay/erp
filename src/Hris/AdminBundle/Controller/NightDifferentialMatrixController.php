<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\NightDifferentialMatrix;
use DateTime;

class NightDifferentialMatrixController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_night_differential_matrix';
        $this->title = 'Night Differential Matrix';

        $this->list_title = 'Night Differential Matrix Entry';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new NightDifferentialMatrix();
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setRate($data['rate']);
        $o->setMinimum($data['amountFrom']);
        $o->setMaximum($data['amountTo']);
        $bracket = $data['amountFrom'].'-'.$data['amountTo'];
        $o->setBracket($bracket);
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

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('HrisAdminBundle:NightDifferentialMatrix:form.html.twig', $params);
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
            $grid->newColumn('Hours From','getMinimum','amount_from'),
            $grid->newColumn('Hours To','getMaximum','amount_to'),
            $grid->newColumn('Rate','getRate','rate'),
        );
    }
}
