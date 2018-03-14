<?php

namespace Gist\LocationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\LocationBundle\Entity\Regions;
use Gist\ValidationException;

class RegionsController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_loc_regions';
        $this->title = 'Region';

        $this->list_title = 'Regions';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Regions();
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


        $twig_file = 'GistLocationBundle:Regions:index.html.twig';
        

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
            $grid->newColumn('Region', 'getName', 'name'),
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

}
