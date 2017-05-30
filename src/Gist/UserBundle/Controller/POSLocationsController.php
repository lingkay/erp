<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\POSLocations;
use Gist\ValidationException;

class POSLocationsController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_loc_pos_locations';
        $this->title = 'POS Locations';

        $this->list_title = 'POS Location';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new POSLocations();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Location', 'getName', 'name'),
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
