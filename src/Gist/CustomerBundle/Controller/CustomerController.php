<?php

namespace Gist\CustomerBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\CustomerBundle\Entity\Customer;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class CustomerController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_customer_list';
        $this->title = 'Customer';

        $this->list_title = 'Customers';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Customer();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    public function viewLogsAction($id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();
        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->find($id);

        $session = $this->getRequest()->getSession();
        $session->set('csrf_token', md5(uniqid()));

        $params = $this->getViewParams('Edit');
        $params['object'] = $obj;
        $params['o_label'] = $this->getObjectLabel($obj);

        // check if we have access to form
        $params['readonly'] = !$this->getUser()->hasAccess($this->route_prefix . '.edit');

        $this->padFormParams($params, $obj);

        return $this->render('GistCustomerBundle:Customer:logs.html.twig', $params);
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistCustomerBundle:Customer:action.html.twig',
            $params
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Customer ID', 'getDisplayID', 'display_id'),
            $grid->newColumn('First Name', 'getFirstName', 'first_name'),
            $grid->newColumn('Last Name', 'getLastName', 'first_name'),
            $grid->newColumn('Email', 'getCEmailAddress', 'c_email_address'),
            $grid->newColumn('Contact Number', 'getMobileNumber', 'mobile_number'),
            $grid->newColumn('Status', 'getStatus', 'status')
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');
        $params['status_opts'] = $am->getStatusOptions();

        $params['gender_options'] = array(
            'male' => 'Male',
            'female' => 'Female'
        );

        $params['marital_options'] = array(
            'single' => 'Single',
            'married' => 'Married',
            'widow' => 'Widow'
        );

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setFirstName($data['first_name']);
        $o->setLastName($data['last_name']);
        $o->setCEmailAddress($data['email']);
        $o->setMobileNumber($data['contact_number']);
        $o->setStatus($data['status']);

        $o->setMiddleName($data['middle_name']);
        $o->setGender($data['gender']);
        $o->setMaritalStatus($data['marital_status']);
        $o->setDateMarried($data['date_married']);
        $o->setHomePhone($data['home_phone']);
        $o->setBirthdate($data['dob']);
        $o->setAddress1($data['address_1']);
        $o->setAddress2($data['address_2']);
        $o->setCity($data['city']);
        $o->setState($data['state']);
        $o->setCountry($data['country']);
        $o->setZip($data['zip']);
        $o->setNotes($data['notes']);


    }

    protected function hookPostSave($obj, $is_new = false)
    {
        //action after save
        $em = $this->getDoctrine()->getManager();
        $new_display_id = str_pad($obj->getID(),9,'0',STR_PAD_LEFT);
        $obj->setDisplayID($new_display_id);
        $em->persist($obj);
        $em->flush();
    }

    public function searchCustomerAction($first_name = null, $last_name = null, $email = null, $number = null, $mname = null, $id = null, $gender = null, $marital_status = null, $date_married = null, $home_phone = null, $birthdate = null, $add1 = null, $add2 = null, $city = null, $state = null, $country = null, $zip = null)
    {
    	header("Access-Control-Allow-Origin: *");

        $search_array = array();
        $search_array['first_name'] = $first_name;
        $search_array['last_name'] = $last_name;
        $search_array['c_email_address'] = $email;
        $search_array['mobile_number'] = $number;
        $search_array['middle_name'] = $mname;
        $search_array['display_id'] = trim($id);
        $search_array['gender'] = $gender;
        $search_array['marital_status'] = $marital_status;
        $search_array['date_married'] = $date_married;
        $search_array['home_phone'] = $home_phone;
        $search_array['birthdate'] = $birthdate;
        $search_array['address1'] = $add1;
        $search_array['address2'] = $add2;
        $search_array['city'] = $city;
        $search_array['state'] = $state;
        $search_array['country'] = $country;
        $search_array['zip'] = $zip;

    	
        $em = $this->getDoctrine()->getManager();

        $customers = $em->getRepository("GistCustomerBundle:Customer")->createQueryBuilder('o');
        foreach ($search_array as $key => $value) {
            if (trim($value) != '') {
                if ($key == 'display_id') {
                    $customers->andWhere('o.'.$key .' = :o_'.$key)
                      ->setParameter('o_'.$key,''.$value.'');
                } else {
                    $customers->andWhere('o.'.$key .' LIKE :o_'.$key)
                      ->setParameter('o_'.$key,'%'.$value.'%');
                }
                
            }
            
        }
		$results = $customers->getQuery()->getResult();

        $list_opts = [];
        foreach ($results as $p) {
			$list_opts[] = array(
                'id'=>$p->getID(), 
                'first_name'=> ($p->getFirstName() == null) ? '':$p->getFirstName(), 
                'last_name'=> ($p->getLastName() == null) ? '':$p->getLastName(), 
                'email'=> ($p->getCEmailAddress() == null) ? '':$p->getCEmailAddress(), 
                'number'=> ($p->getMobileNumber() == null) ? '':$p->getMobileNumber(),
                'middle_name' => ($p->getMiddleName() == null) ? '':$p->getMiddleName(),
                'gender' => ($p->getGender() == null) ? '':$p->getGender(),
                'marital_status' => ($p->getMaritalStatus() == null) ? '':$p->getMaritalStatus(),
                'date_married' => ($p->getDateMarried() == null) ? '':$p->getDateMarried(),
                'home_phone' => ($p->getHomePhone() == null) ? '':$p->getHomePhone(),
                'birthdate' => ($p->getBirthdate() == null) ? '':$p->getBirthdate(),
                'address1' => ($p->getAddress1() == null) ? '':$p->getAddress1(),
                'address2' => ($p->getAddress2() == null) ? '':$p->getAddress2(),
                'city' => ($p->getCity() == null) ? '':$p->getCity(),
                'state' => ($p->getState() == null) ? '':$p->getState(),
                'country' => ($p->getCountry() == null) ? '':$p->getCountry(),
                'zip' => ($p->getZip() == null) ? '':$p->getZip(),
                'notes' => ($p->getNotes() == null) ? '':$p->getNotes(),
            );
        }
        return new JsonResponse($list_opts);
    }

    public function addCustomerAction($first_name = null, $last_name = null, $email = null, $number = null, $mname = null, $gender = null, $marital_status = null, $date_married = null, $home_phone = null, $birthdate = null, $add1 = null, $add2 = null, $city = null, $state = null, $country = null, $zip = null, $notes = null)
    {
    	header("Access-Control-Allow-Origin: *");

    	
    	$em = $this->getDoctrine()->getManager();
    	$customer = new Customer();
    	$customer->setFirstName($first_name);
        $customer->setLastName($last_name);
        $customer->setCEmailAddress($email);
        $customer->setMobileNumber($number);

        $customer->setMiddleName($mname);
        $customer->setGender($gender);
        $customer->setMaritalStatus($marital_status);
        $customer->setDateMarried($date_married);
        $customer->setHomePhone($home_phone);
        $customer->setBirthdate($birthdate);
        $customer->setAddress1($add1);
        $customer->setAddress2($add2);
        $customer->setCity($city);
        $customer->setState($state);
        $customer->setCountry($country);
        $customer->setZip($zip);
        $customer->setNotes($notes);




        $customer->setStatus('Active');

        $em->persist($customer);
        $em->flush();

        $new_display_id = str_pad($customer->getID(),9,'0',STR_PAD_LEFT);
        $customer->setDisplayID($new_display_id);
        $em->persist($customer);
        $em->flush();

        $list_opts[] = array('new_customer_id'=>$customer->getID());
        return new JsonResponse($list_opts);

    }
}
