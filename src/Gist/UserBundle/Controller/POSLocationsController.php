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
        $this->title = 'POS Location';

        $this->list_title = 'POS Locations';
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

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'area', 'getArea'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Location', 'getName', 'name'),
            $grid->newColumn('Area', 'getName', 'name','a'),
            $grid->newColumn('Contact No.', 'getContactNumber', 'contact_number'),
            $grid->newColumn('Location', 'getLocatorDesc', 'locator_desc'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        // enabled options
        $params['type_opts'] = array(
            'Kiosk' => 'Kiosk',
            'Shop' => 'Shop',
            'Inline' => 'Inline',
            'Shop in shop' => 'Shop in shop',
            'Hybrid' => 'Hybrid'
        );

        $params['brand_opts'] = array(
            'Aqua Mineral' => 'Aqua Mineral',
            'Botanifique' => 'Botanifique',
            'ELEVATIONE' => 'ELEVATIONE'
        );

        $params['status_opts'] = array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Deleted' => 'Deleted'
        );

        $params['area_opts'] = $this->getAreaOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setLeasor($data['leasor']);
        $o->setContactNumber($data['contact_number']);
        $o->setCoordinates($data['coordinates']);
        $o->setLocatorDesc($data['locator_desc']);
        $o->setType($data['type']);
        $o->setBrand($data['brand']);
        $o->setCity($data['city']);
        $o->setPostal($data['postal']);
        $o->setRegion($data['region']);
        $o->setCountry($data['country']);
        $o->setStatus($data['status']);

        $em = $this->getDoctrine()->getManager();
        if (isset($data['area'])) {
            $area = $em->getRepository('GistUserBundle:Areas')->find($data['area']);
            $o->setArea($area);
        }

    }

    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $em = $this->getDoctrine()->getManager();
        $objects = $em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    public function getAreaOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:Areas',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }


}
