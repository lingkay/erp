<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManager;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Hris\AdminBundle\Entity\Department;

class DepartmentController extends CrudController
{
    use TrackCreate;
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_department';
        $this->title = 'Department';

        $this->list_title = 'Department';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass() 
    {
        return new Department();
    }
    
    protected function update($o, $data,$is_new = false)
    {
        $wf = $this->get('hris_workforce');
        $settings = $this->get('hris_settings');
        $em = $this->getDoctrine()->getManager();

        $o->setName($data['dept_name']);
        if(isset($data['dept_pos_id']))
            $o->setDeptHead($wf->getEmployee($data['dept_pos_id']));
        if(isset($data['dept_under']))
        {
            if($data['dept_under'] != 0)
            {
                $o->setParent($settings->getDepartment($data['dept_under']));    
            }
            else
            {
                $o->setParent(NULL);
            }
        }
        $this->updateTrackCreate($o, $data, $is_new);
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

        return $this->render('HrisAdminBundle:Department:edit.html.twig', $params);
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
            $grid->newColumn('Department Name', 'getName', 'name'),
            $grid->newColumn('Department Head', 'getDeptHeadName', 'name'),
        );
    }

    public function ajaxFilterDepartmentHeadAction($id)
    {
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();
        $query = $data['query'];

        $employees = $em->getRepository("HrisWorkforceBundle:Employee")->createQueryBuilder('o')
           ->where('o.first_name LIKE :first_name')
           ->orWhere('o.last_name LIKE :last_name')
           ->andWhere('o.department == :department')
           ->setParameter('first_name', "%".$query."%")
           ->setParameter('last_name', "%".$query."%")
           ->setParameter('department', $id)
           ->getQuery()
           ->getResult();

        $list_opts = [];
        foreach ($employees as $employee) {
            $list_opts[] = array('id'=>$employee->getID(), 'name'=> $employee->getDisplayName());
        }
        return new JsonResponse($list_opts);
    }
    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('e', 'dept_head', 'getDeptHead'),
    //     );
    // }
    protected function padFormParams(&$params, $object = NULL)
    {
      
        $settings = $this->get('hris_settings');

        $params['dept_opts'] = $settings->getDepartmentOptions();
    }

    protected function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        if($obj->getDeptHead() != NULL)
        {
            $employee_id = $obj->getDeptHead()->getID();
            $employee = $em->getRepository('GistUserBundle:User')->findOneBy(array("employee" => $employee_id));
            $group = $em->getRepository('GistUserBundle:Group')->findOneBy(array("name" => "dept_head"));

            $emp_groups = explode(',', $employee->getGroupsText());
            if(!in_array("dept_head", $emp_groups))
            {
                $employee->addGroup($group);               
            }

            $em->flush();
        }
    }
}
