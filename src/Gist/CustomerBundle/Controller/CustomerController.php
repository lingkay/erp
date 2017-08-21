<?php

namespace Gist\CustomerBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\CustomerBundle\Entity\Customer;
use Gist\ValidationException;

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
}
