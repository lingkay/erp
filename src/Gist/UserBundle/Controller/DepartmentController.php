<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\Department;
use Gist\ValidationException;

class DepartmentController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_user_department';
        $this->title = 'Department';

        $this->list_title = 'Departments';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Department();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getDepartmentName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Department Name', 'getDepartmentName', 'department_name'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();


        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setDepartmentName($data['department_name']);
    }


}
