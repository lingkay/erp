<?php

namespace Hris\CompanyOverviewBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManager;
use Hris\CompanyOverviewBundle\Entity\ComInfo;
use Gist\ContactBundle\Entity\Address;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\ContactBundle\Template\Controller\HasPhones;


class ComInfoController extends CrudController
{
    use HasPhones;
    use TrackCreate;

    public function __construct()
    {
        $this->route_prefix = 'hris_com_info';
        $this->title = 'Company Information';

        $this->list_title = 'Company Information';
        $this->list_type = 'dynamic';
    }

    public function indexAction()
    {
        $this->checkAccess($this->route_prefix . '.view');
        
        $params = $this->getViewParams('List');
        $conf = $this->get('gist_configuration');
        $cm = $this->get('gist_contact');
        $em = $this->getDoctrine()->getManager();
        $media = $this->get('gist_media');

        $profile = $em->getRepository('HrisCompanyOverviewBundle:ComInfo')->findAll();
        if(count($profile) == 0)
        {
            $obj = $this->newBaseClass(); 
            $path = "";
            $icon = "";
        }
        else
        {
            $obj = array_values($profile)[0];
            if ($conf->get('hris_com_logo') == '') 
            {
                $path = "";
            }
            else
            {
                $path = $media->getUpload($conf->get('hris_com_logo'));
            }

            if ($conf->get('hris_com_favicon') == '') 
            {
                $icon = "";
            }
            else
            {
                $icon = $media->getUpload($conf->get('hris_com_favicon'));
            }
        }


        $this->padFormPhoneType($params);
        $params['object'] = $obj;
        $params['logo'] = $path;
        $params['icon'] = $icon;
        $params['company_name'] = $conf->get('hris_com_info_company_name');
        $params['email_add'] = $conf->get('hris_com_info_email');
        $params['website'] = $conf->get('hris_com_info_website');
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

        //fetch for existing state and city
        

        if(count($conf->get('hris_com_info_company_address')) != 0)
        {
            $add = $cm->getAddress($conf->get('hris_com_info_company_address'));
        }
        else
        {
            $add = null;
        }

        $params['address'] = $add;

        if ($add != null) 
        {
            $state_opts = array();
            $state_opts[$add->getState()->getID()] = $add->getState()->getName();
            $params['state_opts'] = $state_opts;

            $city_opts = array();
            $city_opts[$add->getCity()->getID()] = $add->getCity()->getName();
            $params['city_opts'] = $city_opts;
        }
        else
        {
            $params['state_opts'] = '';
            $params['city_opts'] = '';
        }

        return $this->render('HrisCompanyOverviewBundle:ComInfo:index.html.twig', $params);
    }

    public function indexSubmitAction()
    {
        $this->checkAccess($this->route_prefix . '.add');
        $is_new = false;
        $this->hookPreAction();
        try
        {
            $em = $this->getDoctrine()->getManager();
            $obj = $em->getRepository('HrisCompanyOverviewBundle:ComInfo')->findAll();
            // print_r($obj);
            if(count($obj) == 0)
            {
                $obj = $this->newBaseClass();
                $is_new = true;
                
                $this->update($obj,$is_new);
                $this->addFlash('success', $this->title . ' added successfully.');
            }
            else
            {
                $obj = array_values($obj)[0];

                // update db
                $this->update($obj);
                $this->addFlash('success', $this->title . ' edited successfully.');
            }

            return $this->redirect($this->generateUrl($this->getRouteGen()->getList()));
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

    protected function update($obj,$is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $data = $this->getRequest()->request->all();
        $conf = $this->get('gist_configuration');
        $media = $this->get('gist_media');

        $conf->set('hris_com_info_company_name', $data['company_name']);
        $conf->set('hris_com_info_email', $data['email_add']);
        $conf->set('hris_com_info_website', $data['website']);

        $address = $this->updateAddress($data,$is_new);
        $conf->set('hris_com_info_company_address', $address->getID());

        
        $this->updateHasPhones($obj,$data,$is_new);
        //store phone ids in settings
        $contact = [];
        foreach ($obj->getPhones() as $phone) {
            $contact[] = $phone->getID();
        }

        if($data['logo']!=0 && $data['logo'] != ""){
            $path = $media->getUpload($data['logo']);
            $conf->set('hris_com_logo', $path->getID());

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            //$str = ltrim($str, '/');
            $conf->set('hris_com_logo_url', $str);
            //$conf->set('hris_com_favicon_url', $str);
        }

        if($data['icon']!=0 && $data['icon'] != ""){
            $path = $media->getUpload($data['icon']);
            //$conf->set('hris_com_logo', $path->getID());

            $str = $path->getURL();
            $str = parse_url($str, PHP_URL_PATH);
            //$str = ltrim($str, '/');
            //$conf->set('hris_com_logo_url', $str);
            $conf->set('hris_com_favicon', $path->getID());
            $conf->set('hris_com_favicon_url', $str);
        }

        $conf->set('hris_com_info_phone', json_encode($contact));
        if($is_new)
            $this->updateTrackCreate($obj, $data, $is_new);
            $em->persist($obj);
        $em->flush();       
    }
    
    protected function updateAddress($data,$is_new = false)
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $this->get('gist_contact');

        if($data['address_id'] == 0 || $data['address_id'] == ""){
            $address = $contact->newAddress();
        }else {
            $address = $contact->getAddress($data['address_id']);
        }

        $country = $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['country']);    
        $state = $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['state']); 
        $city = $em->getRepository('HrisAdminBundle:WorldLocation')->find($data['city']); 


        $this->updateTrackCreate($address,$data,$is_new);
        $address->setName($data['unit'])
                ->setStreet($data['street'])
                ->setState($state)
                ->setCity($city)
                ->setCountry($country)
                ->setLongitude($data['longitude'])
                ->setLatitude($data['latitude']);
        $em->persist($address);
        
        //if($is_new){
            $em->flush();
        //}
        return $address;
    }

    protected function getObjectLabel($obj) 
    {
        if ($obj == null){
            return '';
        }
        return '';
    }

    protected function newBaseClass() {
        return new ComInfo;
    }

}