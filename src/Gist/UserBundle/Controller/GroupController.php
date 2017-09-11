<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\Group;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

class GroupController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_user_group';
        $this->title = 'Position';

        $this->list_title = 'Positions';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Group('');
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Position Name', 'getName', 'name'),
            $grid->newColumn('Department', 'getDepartmentName', 'name'),
        );
    }

    public function getPositionsAction($parent)
    {
        $em = $this->getDoctrine()->getManager();
        $filter = array();
        $positions = $em
            ->getRepository('GistUserBundle:Group')
            ->findBy(
                array('department' => $parent)
            );

        $position_opts = array();

        foreach ($positions as $position)
        {
            $position_opts[] =
                [
                    'id' => $position->getID(),
                    'text' => $position->getName(),
                ];
        }


        return new JsonResponse($position_opts);
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');

        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository($this->repo)->findAll();

        $params['objects'] = $objects;


        $twig_file = 'GistUserBundle:Group:index.html.twig';
        

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function update($o, $data, $is_new = false)
    {
//        var_dump($data);
//        die();
        $em = $this->getDoctrine()->getManager();
        $dept = $em->getRepository('GistUserBundle:Department')->find($data['department']);
        $o->setDepartment($dept);

        if (isset($data['head']) && $data['head'] != '-1') {
            if ($data['head'] != '') {
                $position = $em->getRepository('GistUserBundle:Group')->find($data['head']);
                $o->setParent($position);        
            }
            
        }
        else {
            $o->setParent(null);
        }

        if (isset($data['job_description'])) {
            $o->setJobDescription($data['job_description']);
        }
        

        // validate name
        if (strlen($data['name']) > 0)
            $o->setName($data['name']);
        else
            throw new ValidationException('Cannot leave name blank');

        if ($is_new) {
            $o->clearAccess();
            if (isset($data['acl']))
            {
                foreach ($data['acl'] as $id => $val)
                    $o->addAccess($id);
            }
        }
        
    }

    protected function padFormParams(&$params, $object = null)
    {
        $um = $this->get('gist_user');
        $acl_entries = $this->get('gist_acl')->getAllACLEntries();
        // departments
        $params['position_opts'] = $um->getGroupOptions();
        $params['department_opts'] = $um->getDepartmentOptions();
        $params['acl_entries'] = $acl_entries;



        return $params;
    }

    public function deleteAction($id)
    {
        $this->checkAccess($this->route_prefix . '.delete');

        try
        {
            $this->hookPreAction();
            $em = $this->getDoctrine()->getManager();

            $object = $em->getRepository($this->repo)->find($id);
            $odata = $object->toData();

            if($object->getUserCount() == 0 && $object->getVariantCount() == 0)
            {
                $this->logDelete($odata);
                $em->remove($object);
                $em->flush();

                $this->addFlash('success', $this->title . ' ' . $this->getObjectLabel($object) . ' has been deleted.');

                return $this->redirect($this->generateUrl($this->getRouteGen()->getList())); 
            }
            else
            {
                // $this->addFlash('error', 'Could not delete ' . $this->title . ', ' . $object->getUserCount() . ($object->getUserCount() == 1 ? ' user is' : ' users are') . ' using this role.');
                $this->addFlash('error', 'Could not delete ' . $this->title . '.');
                return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
            }
            
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Could not delete ' . $this->title . '.');
            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
        }
    }

    public function orgChartAction()
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        //fix this
        $obj = $em->getRepository($this->repo)->find(21);



        $params = $this->getViewParams('Edit');
        $params['parents'] = $em->getRepository('GistUserBundle:Group')->findBy(array('parent'=>null));

        $departments = $em->getRepository('GistUserBundle:Group')->findAll();
        $list = [];
        foreach($departments as $department)
        {
            if($department->getParent() == NULL || $department->getParent() == 'null')
            {
                $parent = 0;
            }
            else
            {
                $parent = $department->getParent()->getID();
            }
            $dept_head = "";
            
            $list[] = array('id' => $department->getID(), 'name' => $department->getName(), 'description' => $dept_head, 'parent' => $parent);
        }
        $params['departments'] = $list;



        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);
        return $this->render('GistUserBundle:Group:org_chart.html.twig', $params);
    }


    public function aclEditorAction($group_id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($group_id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('GistUserBundle:Group:acl_editor.form.html.twig', $params);
    }

    public function aclUpdateAction($group_id)
    {
        

        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();

        $this->checkAccess($this->route_prefix . '.add');

        $this->hookPreAction();
        try
        {
            $obj = $em->getRepository($this->repo)->find($group_id);
            $obj->clearAccess();
            if (isset($data['acl']))
            {
                foreach ($data['acl'] as $id => $val)
                    $obj->addAccess($id);
            }

            $em->persist($obj);
            $em->flush();

            $this->addFlash('success', $this->title . ' added successfully.');
            if($this->submit_redirect){
                return $this->redirect($this->generateUrl('cat_user_group_acl_editor',array('group_id'=>$obj->getID())));
            }else{
                return $this->redirect($this->generateUrl($this->getRouteGen()->getEdit(),array('id'=>$obj->getID())).$this->url_append);
            }
        }
        catch (ValidationException $e)
        {
             $this->addFlash('error', $e->getMessage());
            return $this->addError($obj);
        }
        catch (DBALException $e)
        {
            $this->addFlash('error', 'Database error encountered. Possible duplicate.');
            error_log($e->getMessage());
            return $this->addError($obj);
        }

    }

}
