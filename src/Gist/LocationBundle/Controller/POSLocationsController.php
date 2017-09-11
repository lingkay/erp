<?php

namespace Gist\LocationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\LocationBundle\Entity\POSLocations;
use Gist\InventoryBundle\Model\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\ValidationException;
use Gist\LocationBundle\Entity\LedgerEntry;

use DateTime;

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
            $grid->newColumn('POS Location', 'getName', 'name'),
            $grid->newColumn('Area', 'getName', 'name','a'),
            $grid->newColumn('Contact No.', 'getContactNumber', 'contact_number'),
            $grid->newColumn('Landmark', 'getLocatorDesc', 'locator_desc'),
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();
        $am = $this->get('gist_accounting');
        $params['bank_opts'] = $am->getBankOptions();

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

        $params['deposit_return_opts'] = array(
            'Yes' => 'Yes',
            'No' => 'No'
        );

        $params['terminals'] = $em->getRepository('GistAccountingBundle:Terminal')->findBy(array('actual_location'=>$o->getID()));
        $params['ledger_entries'] = $em->getRepository('GistLocationBundle:LedgerEntry')->findBy(array('pos_location'=>$o->getID()),array('date_create' => 'DESC'));

        $last_entry = $em->getRepository('GistLocationBundle:LedgerEntry')->findOneBy(array('pos_location'=>$o->getID()),array('date_create' => 'DESC'));

        $params['deposit_returned'] = false;
        if ($last_entry) {
            if ($last_entry->getEntryDescription() == 'Security Deposit Returned') {
                $params['deposit_returned'] = true;
            }
        }

        $params['area_opts'] = $this->getAreaOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $media = $this->get('gist_media');

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
            $area = $em->getRepository('GistLocationBundle:Areas')->find($data['area']);
            $o->setArea($area);
        }



        // PERMITS
        if($data['upl_barangay_clearance']!=0 && $data['upl_barangay_clearance'] != ""){
            $o->setBarangayClearance($media->getUpload($data['upl_barangay_clearance']));
        }

        if (isset($data['exp_barangay_clearance'])) {
            $o->setBarangayClearanceExpiration(new DateTime($data['exp_barangay_clearance']));
        }

        if($data['upl_bir0605']!=0 && $data['upl_bir0605'] != ""){
            $o->setBir0605($media->getUpload($data['upl_bir0605']));
        }

        if (isset($data['exp_bir0605'])) {
            $o->setBir0605Expiration(new DateTime($data['exp_bir0605']));
        }

        if($data['upl_bir0605']!=0 && $data['upl_bir0605'] != ""){
            $o->setBir0605($media->getUpload($data['upl_bir0605']));
        }

        if (isset($data['exp_bir0605'])) {
            $o->setBir0605Expiration(new DateTime($data['exp_bir0605']));
        }
        ///////

        if($data['upl_mayors_permit']!=0 && $data['upl_mayors_permit'] != ""){
            $o->setMayorsPermit($media->getUpload($data['upl_mayors_permit']));
        }

        if (isset($data['exp_mayors_permit'])) {
            $o->setMayorsPermitExpiration(new DateTime($data['exp_mayors_permit']));
        }
        ///////

        if($data['upl_bir2303']!=0 && $data['upl_bir2303'] != ""){
            $o->setBir2303($media->getUpload($data['upl_bir2303']));
        }

        if (isset($data['exp_bir2303'])) {
            $o->setBir2303Expiration(new DateTime($data['exp_bir2303']));
        }
        ///////

        if($data['upl_fire_permit']!=0 && $data['upl_fire_permit'] != ""){
            $o->setFirePermit($media->getUpload($data['upl_fire_permit']));
        }

        if (isset($data['exp_fire_permit'])) {
            $o->setFirePermitExpiration(new DateTime($data['exp_fire_permit']));
        }
        ///////

        if($data['upl_sanitary_permit']!=0 && $data['upl_sanitary_permit'] != ""){
            $o->setSanitaryPermit($media->getUpload($data['upl_sanitary_permit']));
        }

        if (isset($data['exp_sanitary_permit'])) {
            $o->setSanitaryPermitExpiration(new DateTime($data['exp_sanitary_permit']));
        }
        ///////


        // RENTAL
        $o->setRentPaymentAmount($data['rental_payment_amount']);
        $o->setRentPaymentDue($data['rental_due_date']);
        $o->setRentSecurityDepositAmount($data['security_deposit_amount']);
        $o->setRentSecurityDepositReturned($data['security_deposit_returned']);
        $o->setRentSecurityDepositReturnedAmount($data['security_deposit_amount_returned']);
        $o->setRentSecurityDepositRemarks($data['security_deposit_remarks']);

        if($data['design_criteria']!=0 && $data['design_criteria'] != ""){
            $o->setRentDesignCriteria($media->getUpload($data['design_criteria']));
        }

        $o->setRentUnitNumber($data['unit_no']);
        $o->setRentDimension($data['dimension_meter']);
        $o->setRentPricePerSqMeter($data['price_sq_meter']);
        $o->setRentContactPerson($data['contact_person']);
        $o->setRentCpPosition($data['contact_position']);
        $o->setRentCpContactNumber($data['rental_contact_number']);
        $o->setRentCpEmail($data['contact_email']);


        //INSURANCE

        $o->setInsuranceCompany($data['insurance_company']);
        $o->setInsuranceExpiration(new DateTime($data['insurance_expiration']));
        $o->setInsurancePolicy($data['insurance_policy']);

        if($data['insurance_policy_document']!=0 && $data['insurance_policy_document'] != ""){
            $o->setInsurancePolicyDocument($media->getUpload($data['insurance_policy_document']));
        }

        $o->setInsuranceContactPerson1($data['insurance_contact1_person']);
        $o->setInsuranceContactNumber1($data['insurance_contact1_number']);
        $o->setInsuranceContactPerson2($data['insurance_contact2_person']);
        $o->setInsuranceContactNumber2($data['insurance_contact2_number']);

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
            'GistLocationBundle:Areas',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function ajaxLedgerAddEntryAction($pos_location_id, $amount, $date, $description)
    {
        // $list_opts = [];
        // foreach ($employees as $employee) {
        //     $list_opts[] = array('id'=>$employee->getID(), 'name'=> $employee->getDisplayName());
        // }
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_location_id));


        $entry = new LedgerEntry();
        $date_formatted = strtotime($date);
        $entry->setAmount($amount);
        $entry->setEntryDescription($description);
        $entry->setPOSLocation($pos_location);
        $em->persist($entry);
        $em->flush();

        $ledger_total = $pos_location->getLedgerTotal();

        $resp = array('ledger_total'=>number_format($ledger_total,2),'amt'=>number_format($entry->getAmount(),2),'date'=>$entry->getDateCreate()->format('m/d/Y H:i'), 'desc'=>$entry->getEntryDescription());

        return new JsonResponse($resp);
    }

    public function ajaxReturnDepositAction($pos_location_id, $type)
    {
        $em = $this->getDoctrine()->getManager();
        $pos_location = $em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_location_id));

        $last_entry = $em->getRepository('GistLocationBundle:LedgerEntry')->findOneBy(array('pos_location'=>$pos_location_id),array('date_create' => 'DESC'));

        if ($last_entry->getEntryDescription() == 'Security Deposit Returned') {
            $entry = new LedgerEntry();
            $date_formatted = strtotime(date('Y-m-d H:i:s'));
            $entry->setAmount(($last_entry->getAmount())*-1);
            $entry->setEntryDescription('Security Deposit Reverted');
            $entry->setPOSLocation($pos_location);
            $em->persist($entry);
            $em->flush();
            $resp = array('status'=>"OK");
        } else {
            $entry = new LedgerEntry();
            $date_formatted = strtotime(date('Y-m-d H:i:s'));
            $entry->setAmount(($pos_location->getLedgerTotal())*-1);
            $entry->setEntryDescription('Security Deposit Returned');
            $entry->setPOSLocation($pos_location);
            $em->persist($entry);
            $em->flush();
            $resp = array('status'=>"OK");
        }

        
        return new JsonResponse($resp);
    }

}
