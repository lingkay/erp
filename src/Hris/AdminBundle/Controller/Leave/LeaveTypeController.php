<?php

namespace Hris\AdminBundle\Controller\Leave;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Symfony\Component\HttpFoundation\JsonResponse;

use Hris\AdminBundle\Entity\Leave\LeaveType;
use Hris\AdminBundle\Entity\Leave\LeaveRules as Rules;
use Hris\AdminBundle\Entity\Requirements;
use Hris\HrisWorkforceBundle\Entity\EmployeeLeaves;

class LeaveTypeController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_leave_type';
        $this->title = 'Leave Type';
        $this->list_title = 'Leave Type';
        $this->list_type = 'dynamic';
        $this->repo = 'HrisAdminBundle:Leave\LeaveType';
    }

    protected function newBaseClass()
    {
        return new LeaveType();
    }

    protected function padFormParams(&$params, $o = null)
    {
        $params['bool_opts'] = array(0 => 'No', 1=> 'Yes');
        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        $params = [];
        $this->padFormParams($params, $o);

        $o->setName($data['name']);
        $o->setNotes($data['desc']);
        $o->setLeaveCount($data['leave_count']);
        $o->setCollectible($data['collectible']);
        $o->setConvertibleToCash($data['convertible_to_cash']);

        $em->persist($o);
        $em->flush();

        $this->updateTrackCreate($o, $data, $is_new);
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Leave Name', 'getName', 'name'),
            $grid->newColumn('Description', 'getNotes', 'notes'),
            $grid->newColumn('Leaves Count', 'getLeaveCount', 'leave_count'),
        );
    }

    public function addFormAction()
    {
        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        $obj = $this->newBaseClass();

        $params = $this->getViewParams('Add');
        $params['object'] = $obj;
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');
        $this->padFormParams($params, $obj);
        $params['action'] = 'Add New';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }

    protected function addError($obj)
    {
        $params = $this->getViewParams('Add');
        $params['object'] = $obj;
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.add');

        $this->padFormParams($params, $obj);
        $params['action'] = 'Add New';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
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
        $params['action'] = 'Edit';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }

    protected function editError($obj, $id)
    {
        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');
        $this->padFormParams($params, $obj);
        $params['action'] = 'Edit';

        return $this->render('HrisAdminBundle:Leave\LeaveType:form.html.twig', $params);
    }
}
