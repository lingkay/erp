<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\Areas;
use Gist\ValidationException;

class AreasController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_loc_areas';
        $this->title = 'Areas';

        $this->list_title = 'Area';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Areas();
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


}
