<?php

namespace Gist\LocationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\LocationBundle\Entity\Areas;
use Gist\ValidationException;

class AreasController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_loc_areas';
        $this->title = 'Area';

        $this->list_title = 'Areas';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Areas();
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


        $twig_file = 'GistLocationBundle:Areas:index.html.twig';
        

        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();

        return $this->render($twig_file, $params);
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Area', 'getName', 'name'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();


        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
    }

    public function viewGroupAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        
        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        $users = $em->getRepository('GistUserBundle:User')->findBy(array('area'=>$id));
        $params['users'] = $users;

        $pos = $em->getRepository('GistLocationBundle:POSLocations')->findBy(array('area'=>$id));
        $params['pos'] = $pos;

        $positions = $em->getRepository('GistUserBundle:Group')->findAll(array(),array('id'=>'desc'));
        $x = array();
        $count = 0;
        foreach ($positions as $position)
        {
            $count = 0;
            foreach ($position->getUsers() as $user) {
                if ($user->getArea()) {
                    if ($user->getArea()->getID() == $id) {
                        $count++;
                    }   
                }
            }

            if ($count > 0) {
                $x[$position->getName()] = $count;
            }
        }
        $params['positions'] = $x;

        $this->padFormParams($params, $obj);

        return $this->render('GistLocationBundle:Areas:group.html.twig', $params);
    }


}
