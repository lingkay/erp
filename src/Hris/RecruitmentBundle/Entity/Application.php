<?php

namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\MediaBundle\Template\Entity\HasUpload;

use Hris\AdminBundle\Entity\EmploymentStatus;
use Hris\AdminBundle\Entity\Benefit;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app")
 */
class Application
{
    const STATUS_NEW = 'New';
    const STATUS_EXAM = 'Examination';
    const STATUS_INTERVIEW = 'Interview';
    const STATUS_OFFER = 'Job Offer';
    const STATUS_CHECK = 'Background Check';
    const STATUS_CHECKLIST = 'Checklist';
    const STATUS_REQUIREMENTS = 'Requirement';
    const STATUS_SIGNING = 'Contract Signing';
    const STATUS_BLACKLISTEXAM = 'Blacklisted at Examination phase';
    const STATUS_BLACKLISTINTERVIEW = 'Blacklisted at Interview phase';
    const STATUS_BLACKLISTOFFER = 'Blacklisted at Job Offer phase';
    const STATUS_HIRED = 'Hired';
    const STATUS_FAILEDEXAM = 'Failed at Examination';
    const STATUS_FAILEDINTERVIEW = 'Failed at Interview';
    const STATUS_FAILEDOFFER = 'Declined Job Offer';
    const STATUS_FAILEDCHECK = 'Failed at Background Check';
    const STATUS_FAILEDCHECKLIST = 'Did not Submit the Requirements';
    const STATUS_FAILEDSIGNING = 'Did not Sign the Contract';
    
    use HasGeneratedID;
    use TrackCreate;
    use HasUpload;

    /** 
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Benefit")
     * @ORM\JoinColumn(name="benefit_id", referencedColumnName="id")
     */
    protected $benefits;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationProfile", mappedBy="app_id", cascade={"persist"})
     */
    protected $profile;
    
    /**
     * @ORM\OneToMany(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationExperience", mappedBy="app_id", cascade={"persist"})
     */
    protected $experience;

    /**
     * @ORM\OneToMany(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationSkills", mappedBy="app_id", cascade={"persist"})
     */
    protected $skills;

    /**
     * @ORM\OneToMany(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationReference", mappedBy="app_id", cascade={"persist"})
     */
    protected $reference;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationExam", mappedBy="app_id", cascade={"persist"})
     */
    protected $exam;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationInterview", mappedBy="app_id", cascade={"persist"})
     */
    protected $interview;

    /**
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationEducation", mappedBy="app_id", cascade={"persist"})
     */
    protected $education;
    
    /**
     * @ORM\OneToOne(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationInformation", mappedBy="app_id", cascade={"persist"})
     */
    
    protected $information;

    /** @ORM\OneToMany(targetEntity="\Hris\RecruitmentBundle\Entity\ApplicationChecklist", mappedBy="application", cascade={"persist"}) */
    protected $application_checklist;
    
    /** @ORM\Column(type="json_array", nullable=true) */
    protected $choice;

    /** @ORM\Column(type="string", length=20) */
    protected $first_name;

    /** @ORM\Column(type="string", length=20) */
    protected $middle_name;

    /** @ORM\Column(type="string", length=20) */
    protected $last_name;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $nickname;

    /** @ORM\Column(type="string", length=40, nullable=true) */
    protected $app_status;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $email;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $offer_data;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $background_data;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $signing_data;

    /** @ORM\Column(type="string", length=5, nullable=true) */
    protected $appeared;
  
    public function __construct()
    {
        $this->initTrackCreate();
        $this->experience = new ArrayCollection();
        $this->skills = new ArrayCollection();
        $this->reference = new ArrayCollection();
        $this->data = array();
        $this->app_status = self::STATUS_EXAM;
        $this->application_checklist = new ArrayCollection();
    }

    public function addExam(ApplicationExam $exam)
    {
        $exam->setApplication($this);
        $this->exam = $exam;
        return $this;
    }

    public function getExam()
    {
        return $this->exam;
    }

    public function addInterview(ApplicationInterview $interview)
    {
        $interview->setApplication($this);
        $this->interview = $interview;
        return $this;
    }

    public function getInterview()
    {
        return $this->interview;
    }

    public function addProfile(ApplicationProfile $profile)
    {
        $profile->setApplication($this);
        $this->profile = $profile;
        return $this;
    }

    public function getProfile()
    {
        return $this->profile;
    }

    public function addEducation(ApplicationEducation $education)
    {
        $education->setApplication($this);
        $this->education = $education;
        return $this;
    }

    public function getEducation()
    {
        return $this->education;
    }

    public function clearEducation()
    {
        $this->education->clear();
        return $this;
    }

    public function addExperience(ApplicationExperience $experience)
    {
        $experience->setApplication($this);
        $this->experience->add($experience);
        return $this;
    }

    public function getExperience()
    {
        return $this->experience;
    }

    public function clearExperience()
    {
        $this->experience->clear();
        return $this;
    }

    public function addSkills(ApplicationSkills $skills)
    {
        $skills->setApplication($this);
        $this->skills->add($skills);
        return $this;
    }

    public function getSkills()
    {
        return $this->skills;
    }

    public function clearSkills()
    {
        $this->skills->clear();
        return $this;
    }

    public function addReference(ApplicationReference $reference)
    {
        $reference->setApplication($this);
        $this->reference->add($reference);
        return $this;
    }

    public function getReference()
    {
        return $this->reference;
    }

    public function clearReference()
    {
        $this->reference->clear();
        return $this;
    }

    public function addInformation(ApplicationInformation $info)
    {
        $info->setApplication($this);
        $this->information = $info;
        return $this;
    }

    public function getInformation()
    {
        return $this->information;
    }

    public function setChoice($choice)
    {
        $this->choice = $choice;
        return $this;
    }

    public function getChoice()
    {
        return $this->choice;
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

    public function setMiddleName($name)
    {
        $this->middle_name = $name;
        return $this;
    }

    public function getMiddleName()
    {
        return $this->middle_name;
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

    public function setNickName($name)
    {
        $this->nickname = $name;
        return $this;
    }

    public function getFullName()
    {
        return $this->first_name.' '.$this->middle_name.' '.$this->last_name;
    }

    public function getNickname()
    {
        return $this->nickname;
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

    public function setEmailAddress($eadd)
    {
        $this->email = $eadd;
        return $this;
    }

    public function getEmailAddress()
    {
        return $this->email;
    }
    
    public function setApplicationStatus($status)
    {
        $this->app_status = $status;
        return $this;
    }

    public function getApplicationStatus()
    {
        return $this->app_status;
    }

    public function setJobOffer($data)
    {
        $this->offer_data = $data;
        return $this;
    }

    public function getJobOffer()
    {
        return $this->offer_data;
    }

    public function setBackgroundCheck($data)
    {
        $this->background_data = $data;
        return $this;
    }

    public function getBackgroundCheck()
    {
        return $this->background_data;
    }

    public function addChecklist(\Hris\RecruitmentBundle\Entity\ApplicationChecklist $checklist)
    {
        $this->application_checklist->add($checklist);
        return $this;
    }

    public function getChecklist()
    {
        return $this->application_checklist;
    }

    public function clearChecklist()
    {
        return $this->application_checklist->clear();
    }

    public function setContractSigning($data)
    {
        $this->signing_data = $data;
        return $this;
    }

    public function getContractSigning()
    {
        return $this->signing_data;
    }

    public function setAppeared($appeared)
    {   
        $this->appeared = $appeared;
        return $this;
    }

    public function getAppeared()
    {
        return $this->appeared;
    }

    public function getDisplayName()
    {
        return $this->last_name.', '.$this->first_name;
    }

    public function setStatus($status)
    {
        $this->app_status = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->app_status;
    }

    public function getStatusFormatted()
    {
        return ucfirst($this->app_status);
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

}
