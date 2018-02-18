<?php

namespace Gist\LocationBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\LocationBundle\Entity\POSLocations;
use Gist\InventoryBundle\Model\Gallery;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Gist\ValidationException;
use Gist\LocationBundle\Entity\LedgerEntry;
use Gist\InventoryBundle\Template\Controller\HasInventoryAccount;
use Gist\InventoryBundle\Entity\Account;
use Gist\InventoryBundle\Entity\Stock;

use DateTime;

class POSLocationsController extends CrudController
{
    use HasInventoryAccount;

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

        $params['type_opts'] = array(
            'Kiosk' => 'Kiosk',
            'Shop' => 'Shop',
            'Inline' => 'Inline',
            'Shop in shop' => 'Shop in shop',
            'Hybrid' => 'Hybrid'
        );

        $params['counting_rule_opts'] = array(
            'selective' => 'Selective',
            'all' => 'All'
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
        if (isset($data['brand'])) {
            $o->setBrand(implode(",", $data['brand']));
        }
        $o->setCity($data['city']);
        $o->setPostal($data['postal']);
        $o->setRegion($data['region']);
        $o->setCountry($data['country']);
        $o->setStatus($data['status']);

        $o->setCountingRule($data['counting_rule']);

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

    protected function hookPostSave($obj, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        if ($obj->getInventoryAccount()) {

            $this->setupInitialStocks($obj, $obj->getInventoryAccount());

            if (!$obj->getInventoryAccount()->getDamagedContainer()) {
                $newDmgCnt = $this->createDamagedItemsInventoryAccount($obj->getName());
                $obj->getInventoryAccount()->setDamagedContainer($newDmgCnt);
                $em->persist($obj);
                $em->flush();
            }

            if (!$obj->getInventoryAccount()->getMissingContainer()) {
                $newMisCnt = $this->createMissingItemsInventoryAccount($obj->getName());
                $obj->getInventoryAccount()->setMissingContainer($newMisCnt);
                $em->persist($obj);
                $em->flush();
            }

            if (!$obj->getInventoryAccount()->getTesterContainer()) {
                $newTesCnt = $this->createTesterItemsInventoryAccount($obj->getName());
                $obj->getInventoryAccount()->setTesterContainer($newTesCnt);
                $em->persist($obj);
                $em->flush();
            }
        } else {
            $newIacc = $this->createInventoryAccount($obj->getName());

            $obj->setInventoryAccount($newIacc);
            $em->persist($obj);
            $em->flush();

            $newDmgCnt = $this->createDamagedItemsInventoryAccount($obj->getName());
            $newMisCnt = $this->createMissingItemsInventoryAccount($obj->getName());
            $newTesCnt = $this->createTesterItemsInventoryAccount($obj->getName());

            $newIacc->setDamagedContainer($newDmgCnt);
            $newIacc->setMissingContainer($newMisCnt);
            $newIacc->setTesterContainer($newTesCnt);
            $em->persist($newIacc);
            $em->flush();

            $this->setupInitialStocks($obj, $newIacc);
        }
    }

    protected function setupInitialStocks($obj, $wh_acc)
    {

        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        $config = $this->get('gist_configuration');

        //$wh_acc = $obj->getInventoryAccount();
        $existing_stocks = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$wh_acc->getID()));
        $existingProductsArray = [];
        foreach ($existing_stocks as $es) {
            array_push($existingProductsArray, $es->getProduct()->getID());
        }
        //create ZERO stock entries for products
        if ($obj->getBrand() != '') {
            $brands = explode(',', $obj->getBrand());
            foreach ($brands as $brand) {

                $brand_object = $em->getRepository('GistInventoryBundle:Brand')->findOneBy(array('name'=>$brand));

                if ($brand_object) {
                    $brand_id = $brand_object->getID();
                    $products = $em->getRepository('GistInventoryBundle:Product')->findBy(array('brand'=>$brand_id));

                    if ($products) {
                        if (count($existing_stocks) > 0) {
                            //IF LOCATIONS IACC HAS AT LEAST ONE STOCK
                            //CREATE ZERO ENTRY ONLY FOR ITEMS WITHOUT EXISTING RECORDS
                            foreach ($products as $product_object) {
                                if (!in_array($product_object->getID(), $existingProductsArray)) {
                                    //create ZERO entry
                                    $stock_entry = new Stock($wh_acc, $product_object);
                                    $stock_entry->setInventoryAccount($wh_acc);
                                    $stock_entry->setProduct($product_object);
                                    $stock_entry->setQuantity(0);
                                    $em->persist($stock_entry);
                                    $em->flush();
                                }
                            }
                        } else {
                            //IF LOCATIONS IACC HAS NO PRODUCTS AT ALL
                            foreach ($products as $product_object) {
                                //create ZERO entry
                                try {
                                    $stock_entry = new Stock($wh_acc, $product_object);
                                    $stock_entry->setInventoryAccount($wh_acc);
                                    $stock_entry->setProduct($product_object);
                                    $stock_entry->setQuantity(0);
                                    $em->persist($stock_entry);
                                    $em->flush();
                                } catch (\Exception $e) {
                                    die();
                                } catch (\PDOException $e) {

                                }

                            }
                        }
                        //IF BRAND PRODUCTS FOUND
                    }
                    //END IF VALID BRAND
                }
                //END BRANDS LOOP
            }
        }

        //CREATE ENTRIES FOR FIXED ASSETS
        $productsFA = $em->getRepository('GistInventoryBundle:Product')->findBy(array('type'=>'3'));
        if ($productsFA) {
            foreach ($productsFA as $product_objectFA) {
                $existing_stocks = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$wh_acc->getID(), 'product'=>$product_objectFA->getID()));
                //create ZERO entry
                if (count($existing_stocks) <= 0) {
                    $stock_entry = new Stock($wh_acc, $product_objectFA);
                    $stock_entry->setInventoryAccount($wh_acc);
                    $stock_entry->setProduct($product_objectFA);
                    $stock_entry->setQuantity(0);
                    $em->persist($stock_entry);
                    $em->flush();
                }
            }
        }

    }

    protected function createInventoryAccount($name)
    {
        $em = $this->getDoctrine()->getManager();
        $allow = false;
        $account = new Account();
        $account->setName($name)
            ->setUserCreate($this->getUser())
            ->setAllowNegative($allow);

        $em->persist($account);
        $em->flush();


        return $account;
    }

    public function createDamagedItemsInventoryAccount($name)
    {
        $em = $this->getDoctrine()->getManager();
        $dmg_account = new Account();
        $dmg_account->setName('DMG: '.$name)
            ->setUserCreate($this->getUser())
            ->setAllowNegative(false);

        $em->persist($dmg_account);
        $em->flush();

        return $dmg_account;
    }

    public function createMissingItemsInventoryAccount($name)
    {
        $em = $this->getDoctrine()->getManager();
        $account = new Account();
        $account->setName('LOST: '.$name)
            ->setUserCreate($this->getUser())
            ->setAllowNegative(false);

        $em->persist($account);
        $em->flush();

        return $account;
    }

    public function createTesterItemsInventoryAccount($name)
    {
        $em = $this->getDoctrine()->getManager();
        $account = new Account();
        $account->setName('TEST: '.$name)
            ->setUserCreate($this->getUser())
            ->setAllowNegative(false);

        $em->persist($account);
        $em->flush();

        return $account;
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

