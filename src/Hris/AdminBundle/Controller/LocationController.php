<?php

namespace Hris\AdminBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;

use Symfony\Component\HttpFoundation\Response;

use Doctrine\ORM\EntityManager;

use Hris\AdminBundle\Entity\Location;

use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\ContactBundle\Entity\Address;

class LocationController extends CrudController
{
    use TrackCreate;
    
    public function __construct()
    {
        $this->route_prefix = 'hris_admin_location';
        $this->title = 'Location';

        $this->list_title = 'Location';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass() 
    {
        return new Location();
    }
    
    protected function update($o, $data,$is_new = false){
        $o->setName($data['worksite_name']);
        $this->updateTrackCreate($o, $data, $is_new);
        $em = $this->getDoctrine()->getManager();

        $country = $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['country']);      
        $city =    $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['city']);  
        $state =   $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['state']);  
        
        
        $address = new Address();
        $address->setName($data['name'])
                ->setStreet($data['street'])
                ->setCity($city)
                ->setState($state)
                ->setCountry($country)
                ->setIsPrimary(true);
        $address->setUserCreate($this->getUser());
        $em->persist($address);     
        $em->flush();
        $o->setAddress($address);
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return $obj->getName();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('add', 'address', 'getAddress','left'),
        );
    }  

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Location', 'getName', 'name'),
            $grid->newColumn('Unit/Number', 'getName', 'name','add'),
            $grid->newColumn('Street', 'getStreet', 'street','add'),
            $grid->newColumn('City', 'getCityName', 'city','add'), 
        );
    }

    protected function padFormParams(&$params, $object = NULL)
    {
        $em = $this->getDoctrine()->getManager();

        $filter = array();
        $countries = $em
            ->getRepository('HrisAdminBundle:WorldLocation')
            ->findBy(
                array('parent' => 0)
            );

        $country_opts = array();
        
        foreach ($countries as $country)
            $country_opts[$country->getID()] = $country->getName();

            $params['country_opts'] = $country_opts;

        
            if ($object->getAddress() != null) 
            {
                $state_opts = array();
                $state_opts[$object->getAddress()->getState()->getID()] = $object->getAddress()->getState()->getName();
                $params['state_opts'] = $state_opts;

                $city_opts = array();
                $city_opts[$object->getAddress()->getCity()->getID()] = $object->getAddress()->getCity()->getName();
                $params['city_opts'] = $city_opts;
            }
            else
            {
                $params['state_opts'] = '';
                $params['city_opts'] = '';
            }
        return $params;
    }
}
