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

    public function searchCustomerAction($first_name = null, $last_name = null, $email = null, $number = null)
    {
    	header("Access-Control-Allow-Origin: *");
    	// $first_name = mysql_real_escape_string($first_name);
    	// $last_name = mysql_real_escape_string($last_name);
    	// $email = mysql_real_escape_string($email);
    	// $number = mysql_real_escape_string($number);

    	
        $em = $this->getDoctrine()->getManager();
        // $customers = $em->getRepository('GistInventoryBundle:Product')->findBy(array('category'=>$category_id));
        $customers = $em->getRepository("GistCustomerBundle:Customer")->createQueryBuilder('o')
		   ->where('o.c_email_address LIKE :email OR o.first_name LIKE :first_name OR o.last_name LIKE :last_name OR o.mobile_number LIKE :mobile_number')
		   ->setParameter('email', '%'.$email.'%')
		   ->setParameter('first_name', '%'.$first_name.'%')
		   ->setParameter('last_name', '%'.$last_name.'%')
		   ->setParameter('mobile_number', '%'.$number.'%')
		   ->getQuery()
		   ->getResult();
     
        $list_opts = [];
        foreach ($customers as $p) {
			$list_opts[] = array('id'=>$p->getID(), 'first_name'=> $p->getFirstName(), 'last_name'=> $p->getLastName(), 'email'=> $p->getCEmailAddress(), 'number'=> $p->getMobileNumber());
        }
        return new JsonResponse($list_opts);
    }

    public function addCustomerAction($first_name = null, $last_name = null, $email = null, $number = null)
    {
    	header("Access-Control-Allow-Origin: *");
    	// $first_name = mysql_real_escape_string($first_name);
    	// $last_name = mysql_real_escape_string($last_name);
    	// $email = mysql_real_escape_string($email);
    	// $number = mysql_real_escape_string($number);

    	
    	$em = $this->getDoctrine()->getManager();
    	$customer = new Customer();
    	$customer->setFirstName($first_name);
        $customer->setLastName($last_name);
        $customer->setCEmailAddress($email);
        $customer->setMobileNumber($number);
        $customer->setStatus('Active');

        $em->persist($customer);
        $em->flush();

        $list_opts[] = array('new_customer_id'=>$customer->getID());
        return new JsonResponse($list_opts);

    }
}
