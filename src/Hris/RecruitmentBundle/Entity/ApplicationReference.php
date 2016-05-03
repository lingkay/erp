<?php

namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use Catalyst\ContactBundle\Entity\Phone;
use Hris\RecruitmentBundle\Entity\Application;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_reference")
 */
class ApplicationReference
{

    use HasGeneratedID;
    use TrackCreate;

    /** 
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="reference")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** 
     * @ORM\ManyToOne(targetEntity="\Catalyst\ContactBundle\Entity\Phone") 
     * @ORM\JoinColumn(name="phone_id", referencedColumnName="id")
     */
    protected $phone; 

    /** @ORM\Column(type="string") */
    protected $first_name;

    /** @ORM\Column(type="string") */
    protected $middle_name;

    /** @ORM\Column(type="string") */
    protected $last_name;

    /** @ORM\Column(type="string") */
    protected $salutation;

    /** @ORM\Column(type="string") */
    protected $relationship;

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

    public function setPhone(Phone $phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

   public function setFirstName($name)
    {
        $this->first_name = $name;
        return $this;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function setLastName($name)
    {
        $this->last_name = $name;
        return $this;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function setMiddleName($name)
    {
        $this->middle_name = $name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
    }

    public function setSalutation($sal)
    {
        $this->salutation = $sal;
        return $this;
    }

    public function getSalutation()
    {
        return $this->salutation;
    }

    public function setRelationship($relationship)
    {
        $this->relationship = $relationship;
        return $this;
    }

    public function getRelationship()
    {
        return $this->relationship;
    }

    public function getDisplayName()
    {
        return $this->salutation.' '.$this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);
        return $data;
    }

}

?>