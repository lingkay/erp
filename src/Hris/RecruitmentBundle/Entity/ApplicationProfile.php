<?php

namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use Gist\ContactBundle\Entity\Phone;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_profile")
 */
class ApplicationProfile
{   
    use HasGeneratedID;
    use TrackCreate;

    /** 
     * @ORM\OneToOne(targetEntity="Application", inversedBy="profile")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** 
     * @ORM\OneToOne(targetEntity="\Gist\ContactBundle\Entity\Phone") 
     * @ORM\JoinColumn(name="my_phone_id", referencedColumnName="id")
     */
    protected $my_number;

    /** 
     * @ORM\OneToOne(targetEntity="\Gist\ContactBundle\Entity\Phone") 
     * @ORM\JoinColumn(name="contact_phone_id", referencedColumnName="id")
     */
    protected $contact_number;

    /** 
     * @ORM\OneToOne(targetEntity="\Gist\ContactBundle\Entity\Address") 
     * @ORM\JoinColumn(name="home_address", referencedColumnName="id")
     */
    protected $home_address;

    /** 
     * @ORM\OneToOne(targetEntity="\Gist\ContactBundle\Entity\Address") 
     * @ORM\JoinColumn(name="permanent_address", referencedColumnName="id")
     */
    protected $permanent_address;

    /** @ORM\Column(type="datetime") */
    protected $birth_date;

    /** @ORM\Column(type="string", length=80) */
    protected $birth_place;

    /** @ORM\Column(type="integer") */
    protected $height;

    /** @ORM\Column(type="integer") */
    protected $weight;

    /** @ORM\Column(type="string", length=50) */
    protected $contact_person;

    /** @ORM\Column(type="smallint") */
    protected $civil_status;

    /** @ORM\Column(type="smallint") */
    protected $no_dependents;

    /** @ORM\Column(type="string", length=80) */
    protected $spouse_name;

    /** @ORM\Column(type="smallint") */
    protected $no_children;

    /** @ORM\Column(type="string", length=80) */
    protected $father_name;

    /** @ORM\Column(type="string", length=80) */
    protected $father_occupation;

    /** @ORM\Column(type="string", length=80) */
    protected $mother_name;

    /** @ORM\Column(type="string", length=80) */
    protected $mother_occupation;

    /** @ORM\Column(type="string", length=10,nullable=true) */
    protected $gender;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setApplication(Application $application)
    {
        $this->app_id = $application;
        return $this;
    }

    public function getApplication()
    {
        return $this->app_id;
    }
    
    public function setBirthDate(DateTime $date)
    {
        $this->birth_date = $date;
        return $this;
    }

    public function getBirthDate()
    {
        return $this->birth_date;            
    }

    public function getBirthDateDisplay()
    {
        return $this->birth_date->format('m/d/Y');
    }

    public function setBirthPlace($place)
    {
        $this->birth_place = $place;
        return $this;
    }

    public function getBirthPlace()
    {
        return $this->birth_place;
    }

    public function setHeight($height)
    {
        $this->height = $height;
        return $this;
    }

    public function getHeight()
    {
        return $this->height;
    }

    public function setWeight($weight)
    {
        $this->weight = $weight;
        return $this;
    }

    public function getWeight()
    {
        return $this->weight;
    }

    public function setContactPerson($name)
    {
        $this->contact_person = $name;
        return $this;
    }

    public function getContactPerson()
    {
        return $this->contact_person;
    }

    public function setMyNumber(Phone $phone)
    {
        $this->my_number = $phone;
        return $this;
    }

    public function getMyNumber()
    {
        return $this->my_number;
    }

    public function setContactNumber(Phone $phone)
    {
        $this->contact_number = $phone;
        return $this;
    }

    public function getContactNumber()
    {
        return $this->contact_number;
    }

    public function setCivilStatus($status)
    {
        $this->civil_status = $status;
        return $this;
    }

    public function getCivilStatus()
    {
        return $this->civil_status;
    }

    public function setNumberOfDependents($no)
    {
        $this->no_dependents = $no;
        return $this;
    }

    public function getNumberOfDependents()
    {
        return $this->no_dependents;
    }

    public function setSpouseName($name)
    {
        $this->spouse_name = $name;
        return $this;
    }

    public function getSpouseName()
    {
        return $this->spouse_name;
    }

    public function setNumberOfChildren($no)
    {
        $this->no_children = $no;
        return $this;
    }

    public function getNumberOfChildren()
    {
        return $this->no_children;
    }

    public function setFatherName($name)
    {
        $this->father_name = $name;
        return $this;
    }

    public function getFatherName()
    {
        return $this->father_name;
    }

    public function setFatherOccupation($occupation)
    {
        $this->father_occupation = $occupation;
        return $this;
    }

    public function getFatherOccupation()
    {
        return $this->father_occupation;
    }

    public function setMotherName($name)
    {
        $this->mother_name = $name;
        return $this;
    }

    public function getMotherName()
    {
        return $this->mother_name;
    }

    public function setMotherOccupation($occupation)
    {
        $this->mother_occupation = $occupation;
        return $this;
    }

    public function getMotherOccupation()
    {
        return $this->mother_occupation;
    }
    public function getDisplayName()
    {
        return $this->last_name.', '.$this->first_name;
    }

    public function setHomeAddress(\Gist\ContactBundle\Entity\Address $add)
    {
        $this->home_address = $add;
        return $this;
    }

    public function getHomeAddress()
    {
        return $this->home_address;
    }

    public function setPermanentAddress(\Gist\ContactBundle\Entity\Address $add)
    {
        $this->permanent_address = $add;
        return $this;
    }

    public function getPermanentAddress()
    {
        return $this->permanent_address;
    }

    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    public function getGender()
    {
        return $this->gender;
    }
    
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

}
