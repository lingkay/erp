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

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
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

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setFirstName($data['first_name']);
        $o->setLastName($data['last_name']);
        $o->setCEmailAddress($data['email']);
        $o->setMobileNumber($data['contact_number']);
        $o->setStatus($data['status']);

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
        $search_array['id'] = $id;
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
                $customers->andWhere($customers->expr()->eq('o.'.$key, ':o_'.$key))
                      ->setParameter('o_'.$key,''.$value.'');
            }
            
        }
		$results = $customers->getQuery()->getResult();


     
        $list_opts = [];
        foreach ($results as $p) {
			$list_opts[] = array(
                'id'=>$p->getID(), 
                'first_name'=> $p->getFirstName(), 
                'last_name'=> $p->getLastName(), 
                'email'=> $p->getCEmailAddress(), 
                'number'=> $p->getMobileNumber(),
                'middle_name' => $p->getMiddleName(),
                'id' => $p->getID(),
                'gender' => $p->getGender(),
                'marital_status' => $p->getMaritalStatus(),
                'date_married' => $p->getDateMarried(),
                'home_phone' => $p->getHomePhone(),
                'birthdate' => $p->getBirthdate(),
                'address1' => $p->getAddress1(),
                'address2' => $p->getAddress2(),
                'city' => $p->getCity(),
                'state' => $p->getState(),
                'country' => $p->getCountry(),
                'zip' => $p->getZip(),
            );
        }
        return new JsonResponse($list_opts);
    }

    public function addCustomerAction($first_name = null, $last_name = null, $email = null, $number = null, $mname = null, $id = null, $gender = null, $marital_status = null, $date_married = null, $home_phone = null, $birthdate = null, $add1 = null, $add2 = null, $city = null, $state = null, $country = null, $zip = null)
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


        $customer->setStatus('Active');

        $em->persist($customer);
        $em->flush();

        $list_opts[] = array('new_customer_id'=>$customer->getID());
        return new JsonResponse($list_opts);

    }
}
