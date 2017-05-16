<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\User;
use Gist\InventoryBundle\Model\Gallery;
use Gist\ValidationException;
use DateTime;

class UserController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_user_user';
        $this->title = 'User';

        $this->list_title = 'Users';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new User();
    }
    
    protected function getObjectLabel($obj)
    {
        return $obj->getUsername();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Username', 'getUsername', 'username'),
            $grid->newColumn('Email', 'getEmail', 'email'),
            $grid->newColumn('Name', 'getName', 'name'),
            // $grid->newColumn('Roles', 'getGroupsText', 'id', 'o', null, false),
            $grid->newColumn('Last Login', 'getLastLoginText', 'lastLogin', 'o', null, false),
            $grid->newColumn('Status', 'getEnabledText', 'enabled', 'o', null, false),
        );
    }

    protected function getGallery($id)
    {
        return new Gallery(__DIR__ . '/../../../../web/uploads/dzones', $id);
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $um = $this->get('gist_user');

        if ($user->getID())
        {
            $gallery = $this->getGallery($user->getID());
            $images = $gallery->getImages();
            $params['images'] = $images;
        }

        // enabled options
        $params['enabled_opts'] = array(
            1 => 'Enabled',
            0 => 'Disabled'
        );

        $params['agency_opts'] = array(
            1 => 'Agency X',
            0 => 'Agency Y'
        );

        $params['area_opts'] = array(
            'Makati' => 'Makati',
            'Taguig' => 'Taguig'
        );

        $params['brand_opts'] = array(
            'Barand X' => 'Brand X',
            'Brand Y' => 'Brand Y'
        );

        $params['nationality_opts'] = array(
            'Filipino' => 'Filipino',
            'French' => 'French'
        );

        $params['commission_type_opts'] = array(
            'Straight' => 'Straight',
            'Variable' => 'Variable'
        );
        
        $params['approver_opts'] = array(
            'Approver 1' => 'Approver 1',
            'Approver 2' => 'Approver 2'
        );

        // groups
        $params['position_opts'] = $um->getGroupOptions();

        // departments
        $params['department_opts'] = $um->getDepartmentOptions();

        // user groups
        $ug_opts = array();
        if ($user != null)
        {
            $ugroups = $user->getGroups();
            foreach ($ugroups as $ug)
                $ug_opts[$ug->getID()] = $ug->getName();
        }
        $params['ug_opts'] = $ug_opts;

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {

        // var_dump($data);
        // die();
        $em = $this->getDoctrine()->getManager();
        $uc = $this->get('gist_user');

        //update position/group
        if (isset($data['position'])) {
            $position = $em->getRepository('GistUserBundle:Group')->find($data['position']);
            $o->setGroup($position);
        }

        if (isset($data['agency'])) {
            $o->setAgencyName($data['agency']);
        }

        if (isset($data['approver'])) {
            $o->setApprover($data['approver']);
        }

        if (isset($data['area'])) {
            $o->setArea($data['area']);
        }

        if (isset($data['brand'])) {
            $o->setBrand($data['brand']);
        }

        if (isset($data['commission_type'])) {
            $o->setCommissionType($data['commission_type']);
        }

        if (isset($data['first_name'])) {
            $o->setFirstName($data['first_name']);
        }

        if (isset($data['middle_name'])) {
            $o->setMiddleName($data['middle_name']);
        }

        if (isset($data['last_name'])) {
            $o->setLastName($data['last_name']);
        }

        if (isset($data['conctact_no'])) {
            $o->setContactNumber($data['conctact_no']);
        }

        if (isset($data['nationality'])) {
            $o->setNationality($data['nationality']);
        }
        
        if (isset($data['dob'])) {
            $o->setDateOfBirth(new DateTime($data['dob']));
        }

        if (isset($data['prov_address'])) {
            $o->setProvincialAddress($data['prov_address']);
        }

        if (isset($data['city_address'])) {
            $o->setCityAddress($data['city_address']);
        }

        if (isset($data['life_insurance'])) {
            $o->setLifeInsurance($data['life_insurance']);
        }

        if (isset($data['life_insurance_exp'])) {
            $o->setLifeInsuranceExpiration(new DateTime($data['life_insurance_exp']));
        }

        if (isset($data['sss_no'])) {
            $o->setSSS($data['sss_no']);
        }

        if (isset($data['philhealth_no'])) {
            $o->setPhilhealth($data['philhealth_no']);
        }

        if (isset($data['pagibig_no'])) {
            $o->setPagibig($data['pagibig_no']);
        }

        if (isset($data['tin_no'])) {
            $o->setTIN($data['tin_no']);
        }

        if (isset($data['ec_full_name'])) {
            $o->setECFullName($data['ec_full_name']);
        }

        if (isset($data['ec_relationship'])) {
            $o->setECRelation($data['ec_relationship']);
        }

        if (isset($data['ec_contact_no'])) {
            $o->setECContact($data['ec_contact_no']);
        }

        if (isset($data['ec_remarks'])) {
            $o->setECRemarks($data['ec_remarks']);
        }

        if (isset($data['employment_date'])) {
            $o->setEmploymentDate(new DateTime($data['employment_date']));
        }

        if (isset($data['contract_expiration'])) {
            $o->setContractExpiration(new DateTime($data['contract_expiration']));
        }

        if (isset($data['contract_status'])) {
            $o->setContractStataus($data['contract_status']);
        }

        if (isset($data['employment_remarks'])) {
            $o->setEmploymentRemarks($data['employment_remarks']);
        }

        $media = $this->get('gist_media');

        // var_dump($media->getUpload($data['upl_employment_contract']));
        //     die();
        if($data['upl_employment_contract']!=0 && $data['upl_employment_contract'] != ""){
            $o->setFileEmploymentContract($media->getUpload($data['upl_employment_contract']));
        }

        if($data['upl_police_clearance']!=0 && $data['upl_police_clearance'] != ""){
            $o->setFilePoliceClearance($media->getUpload($data['upl_police_clearance']));
        }

        if($data['upl_nbi_clearance']!=0 && $data['upl_nbi_clearance'] != ""){
            $o->setFileNBIClearance($media->getUpload($data['upl_nbi_clearance']));
        }

        if($data['upl_prev_coe']!=0 && $data['upl_prev_coe'] != ""){
            $o->setFilePrevCOE($media->getUpload($data['upl_prev_coe']));
        }



        // TODO: validation check for email
        // check if username is set then set username, else throw exception
        if (strlen($data['username']) > 0)
            $o->setUsername($data['username']);
        else
            throw new ValidationException('Cannot leave username blank');

        $o->setEmail($data['email']);
        $o->setName($data['first_name']);

        // status / enabled
        if ($data['enabled'] == 1)
            $o->setEnabled(1);
        else
            $o->setEnabled(0);



        // check if we need to have password
        if ($is_new)
        {
            if (strlen($data['pass1']) <= 0)
                throw new ValidationException('Cannot leave password blank');

            if (strlen($data['pass2']) <= 0)
                throw new ValidationException('Cannot leave password blank');
        }

        // check if passwords match
        if (strlen($data['pass1']) > 0)
        {
            if ($data['pass1'] != $data['pass2'])
                throw new ValidationException('Passwords do not match');

            $um = $this->container->get('fos_user.user_manager');
            $o->setPlainPassword($data['pass1']);
            $um->updatePassword($o);
        }
    }

    public function toData()
    {
        $data = array(
            'warehouse_id' => $o->getWarehouse()->getID(),
            );

        return $data;
    }

    public function deleteFileAction($file)
    {
        unlink($file);
    }
}
