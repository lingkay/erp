<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\OvertimeMatrix;
use DateTime;

class OvertimeMatrixController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_overtime_matrix';
        $this->title = 'Overtime Matrix';

        $this->list_title = 'Overtime Matrix Entry';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new OvertimeMatrix();
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setRate($data['rate']);
        $o->setMinimum(0);
        $o->setMaximum($data['amountTo']);
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

        return $this->render('HrisAdminBundle:OvertimeMatrix:form.html.twig', $params);
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
            $grid->newColumn('# of Hours','getMaximum','amount_to'),
            $grid->newColumn('Rate','getRate','rate'),
        );
    }
}
