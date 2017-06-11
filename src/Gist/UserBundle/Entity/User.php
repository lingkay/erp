<?php

namespace Gist\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Model\GroupInterface;
use Gist\MediaBundle\Entity\Upload;
use stdClass;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_user")
 * @ORM\AttributeOverrides({
 *      @ORM\AttributeOverride(name="email", column=@ORM\Column(type="string", name="email", length=255, unique=false, nullable=true)),
 *      @ORM\AttributeOverride(name="emailCanonical", column=@ORM\Column(type="string", name="email_canonical", length=255, unique=false, nullable=true))
 * })
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Group")
     * @ORM\JoinColumn(name="group_id", referencedColumnName="id")
     */
    protected $group;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="emp_contract_file_id", referencedColumnName="id")
     */
    protected $profile_picture;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="emp_contract_file_id", referencedColumnName="id")
     */
    protected $file_employment_contract;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="nbi_clearance_file_id", referencedColumnName="id")
     */
    protected $file_nbi_clearance;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="police_clearance_file_id", referencedColumnName="id")
     */
    protected $file_police_clearance;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\MediaBundle\Entity\Upload")
     * @ORM\JoinColumn(name="previous_coe_file_id", referencedColumnName="id")
     */
    protected $file_previous_coe;


    /** @ORM\Column(type="string", length=50, nullable=true) */
    protected $name;

    protected $acl_cache;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee", inversedBy="user", cascade={"persist"})
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
    *
    * @ORM\Column(type="boolean") 
    */
    protected $flag_emailnotify;

    // NEW FIELDS AS OF MAY 14, 2017

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $first_name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $middle_name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $last_name;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $agency;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $approver;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $approver_code;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\LocationBundle\Entity\Areas")
     * @ORM\JoinColumn(name="area_id", referencedColumnName="id")
     */
    protected $area;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\InventoryBundle\Entity\Brand")
     * @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
     */
    protected $brand;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $commission_type;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $contact_number;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $nationality;

    /** @ORM\Column(type="date") */
    protected $date_of_birth;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $provincial_address;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $city_address;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $life_insurance;

    /** @ORM\Column(type="date") */
    protected $life_insurance_expiration;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $sss;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $philhealth;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $pagibig;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $tin;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $ec_full_name;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $ec_contact_number;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $ec_relationship;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $ec_remarks;

    /** @ORM\Column(type="date") */
    protected $employment_date;

    /** @ORM\Column(type="date") */
    protected $contract_expiration;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $contract_status;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $employment_remarks;
    
    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $items_given;


    public function __construct()
    {
        parent::__construct();
        $this->roles = array();
        // $this->groups = new ArrayCollection();
        $this->acl_cache = array();
        $this->flag_emailnotify = true;
    }

    public function setFirstName($first_name)
    {
        $this->first_name = $first_name;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setMiddleName($middle_name)
    {
        $this->middle_name = $middle_name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function setLastName($last_name)
    {
        $this->last_name = $last_name;
        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setAgencyName($agency)
    {
        $this->agency = $agency;
        return $this;
    }

    public function getAgencyName()
    {
        return $this->agency;
    }

    public function setApprover($approver)
    {
        $this->approver = $approver;
        return $this;
    }

    public function getApprover()
    {
        return $this->approver;
    }

    public function setApproverCode($approver_code)
    {
        $this->approver_code = $approver_code;
        return $this;
    }

    public function getApproverCode()
    {
        return $this->approver_code;
    }

    public function setArea($area)
    {
        $this->area = $area;
        return $this;
    }

    public function getArea()
    {
        return $this->area;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setCommissionType($commission_type)
    {
        $this->commission_type = $commission_type;
        return $this;
    }

    public function getCommissionType()
    {
        return $this->commission_type;
    }

    public function setContactNumber($contact_number)
    {
        $this->contact_number = $contact_number;
        return $this;
    }

    public function getContactNumber()
    {
        return $this->contact_number;
    }

    public function setNationality($nationality)
    {
        $this->nationality = $nationality;
        return $this;
    }

    public function getNationality()
    {
        return $this->nationality;
    }

    public function setDateOfBirth(DateTime $date_of_birth)
    {
        $this->date_of_birth = $date_of_birth;
        return $this;
    }

    public function getDateOfBirth()
    {
        return $this->date_of_birth;
    }

    public function setProvincialAddress($provincial_address)
    {
        $this->provincial_address = $provincial_address;
        return $this;
    }

    public function getProvincialAddress()
    {
        return $this->provincial_address;
    }

    public function setCityAddress($city_address)
    {
        $this->city_address = $city_address;
        return $this;
    }

    public function getCityAddress()
    {
        return $this->city_address;
    }

    public function setLifeInsurance($life_insurance)
    {
        $this->life_insurance = $life_insurance;
        return $this;
    }

    public function getLifeInsurance()
    {
        return $this->life_insurance;
    }

    public function setLifeInsuranceExpiration(DateTime $life_insurance_expiration)
    {
        $this->life_insurance_expiration = $life_insurance_expiration;
        return $this;
    }

    public function getLifeInsuranceExpiration()
    {
        return $this->life_insurance_expiration;
    }

    public function setSSS($sss)
    {
        $this->sss = $sss;
        return $this;
    }

    public function getSSS()
    {
        return $this->sss;
    }

    public function setPhilhealth($philhealth)
    {
        $this->philhealth = $philhealth;
        return $this;
    }

    public function getPhilhealth()
    {
        return $this->philhealth;
    }

    public function setPagibig($pagibig)
    {
        $this->pagibig = $pagibig;
        return $this;
    }

    public function getPagibig()
    {
        return $this->pagibig;
    }

    public function setTIN($tin)
    {
        $this->tin = $tin;
        return $this;
    }

    public function getTIN()
    {
        return $this->tin;
    }

    public function setECFullName($ec_full_name)
    {
        $this->ec_full_name = $ec_full_name;
        return $this;
    }

    public function getECFullName()
    {
        return $this->ec_full_name;
    }

    public function setECRelation($ec_relationship)
    {
        $this->ec_relationship = $ec_relationship;
        return $this;
    }

    public function getECRelation()
    {
        return $this->ec_relationship;
    }

    public function setECContact($ec_contact_number)
    {
        $this->ec_contact_number = $ec_contact_number;
        return $this;
    }

    public function getECContact()
    {
        return $this->ec_contact_number;
    }

    public function setECRemarks($ec_remarks)
    {
        $this->ec_remarks = $ec_remarks;
        return $this;
    }

    public function getECRemarks()
    {
        return $this->ec_remarks;
    }

    public function setEmploymentDate(DateTime $employment_date)
    {
        $this->employment_date = $employment_date;
        return $this;
    }

    public function getEmploymentDate()
    {
        return $this->employment_date;
    }

    public function setContractExpiration(DateTime $contract_expiration)
    {
        $this->contract_expiration = $contract_expiration;
        return $this;
    }

    public function getContractExpiration()
    {
        return $this->contract_expiration;
    }

    public function setContractStataus($contract_status)
    {
        $this->contract_status = $contract_status;
        return $this;
    }

    public function getContractStataus()
    {
        return $this->contract_status;
    }

    public function setEmploymentRemarks($employment_remarks)
    {
        $this->employment_remarks = $employment_remarks;
        return $this;
    }

    public function getEmploymentRemarks()
    {
        return $this->employment_remarks;
    }

    public function setProfilePicture(Upload $profile_picture)
    {
        $this->profile_picture = $profile_picture;
        return $this;
    }

    public function getProfilePicture()
    {
        return $this->profile_picture;
    }

    public function setFileEmploymentContract(Upload $file_employment_contract)
    {
        $this->file_employment_contract = $file_employment_contract;
        return $this;
    }

    public function getFileEmploymentContract()
    {
        return $this->file_employment_contract;
    }

    public function setFilePoliceClearance(Upload $file_police_clearance)
    {
        $this->file_police_clearance = $file_police_clearance;
        return $this;
    }

    public function getFilePoliceClearance()
    {
        return $this->file_police_clearance;
    }

    public function setFileNBIClearance(Upload $file_NBI_clearance)
    {
        $this->file_nbi_clearance = $file_NBI_clearance;
        return $this;
    }

    public function getFileNBIClearance()
    {
        return $this->file_nbi_clearance;
    }

    public function setFilePrevCOE(Upload $file_previous_coe)
    {
        $this->file_previous_coe = $file_previous_coe;
        return $this;
    }

    public function getFilePrevCOE()
    {
        return $this->file_previous_coe;
    }

    public function setItemsGiven($items_given)
    {
        $this->items_given = $items_given;
        return $this;
    }

    public function getItemsGiven()
    {
        return $this->items_given;
    }

    public function setID($id)
    {
        $this->id = $id;
        return $this;
    }


    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getID()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getLastLoginText()
    {
        if ($this->getLastLogin() == null)
            return 'Never';
        return $this->getLastLogin()->format('M d, Y - H:m:s');
    }

    public function getEnabledText()
    {
        if ($this->enabled)
            return 'Enabled';
        return 'Disabled';
    }

    public function hasAccess($acl_key)
    {
        // DEBUG: allow all for admin user
        // if ($this->getUsername() == 'admin')
          //   return true;

        // check acl cache
        if (isset($this->acl_cache[$acl_key]))
            return $this->acl_cache[$acl_key];

        // go through all groups and check
        
        if ($this->group->hasAccess($acl_key))
        {
            $this->acl_cache[$acl_key] = true;
            return true;
        }
        
        // no access
        $this->acl_cache[$acl_key] = false;
        return false;
    }

     public function setEmployee($employee)
    {
        $this->employee = $employee;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

     public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function isEmailNotify()
    {
        return $this->flag_emailnotify;
    }

    public function setEmailNotify($flag)
    {
        $this->flag_emailnotify = $flag;
        return $this;
    }



    public function toData()
    {
        $groups = array();
        $data = new stdClass();
        $data->id = $this->id;
        $data->username = $this->username;
        $data->email = $this->email;
        $data->enabled = $this->enabled;
        $data->groups = $groups;

        return $data;
    }
}
