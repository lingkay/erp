<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Supplier;
use Gist\InventoryBundle\Model\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\ValidationException;

use DateTime;

class SupplierController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_supp';
        $this->title = 'Supplier';

        $this->list_title = 'Suppliers';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Supplier();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }


    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            // $grid->newColumn('Area', 'getName', 'name','a'),
            // $grid->newColumn('Contact No.', 'getContactNumber', 'contact_number'),
            // $grid->newColumn('Location', 'getLocatorDesc', 'locator_desc'),
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $im = $this->get('gist_inventory');
        $am = $this->get('gist_accounting');

        $params['type_opts'] = $im->getSupplierTypeOptions();
        $params['category_opts'] = $im->getSupplierCategoryOptions();
        $params['tax_opts'] = $im->getSupplierTaxOptions();
        $params['status_opts'] = $am->getStatusOptions();
        
        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        //$media = $this->get('gist_media');

        $o->setName($data['name']);
        $o->setType($data['type']);
        $o->setFirstName($data['first_name']);
        $o->setMiddleName($data['middle_name']);
        $o->setLastName($data['last_name']);
        $o->setTIN($data['tin']);
        $o->setCategory($data['category']);
        $o->setTax($data['tax']);
        $o->setContactPerson($data['contact_person']);
        $o->setShipmentPeriod($data['shipment_period']);
        $o->setTelephone($data['telephone']);
        $o->setMobile($data['mobile']);
        $o->setFax($data['fax']);
        $o->setWebsite($data['website']);
        $o->setEmail($data['email']);
        $o->setStatus($data['status']);
        
    }
}
