<?php

namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use Gist\CoreBundle\Template\Entity\HasNotes;

use Hris\AdminBundle\Entity\Department;
use Hris\AdminBundle\Entity\JobTitle;
use Hris\WorkforceBundle\Entity\Employee;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_manpower_request")
 */
class ManpowerRequest
{

    const STATUS_DRAFT = 'Draft';
    const STATUS_SENT = 'Sent';
    const STATUS_REVIEW = 'Reviewed';
    const STATUS_DENIED = 'Denied';
    const STATUS_APPROVED = 'Approved';
    const STATUS_FILED = 'Filed';
    const STATUS_ARCHIVED = 'Archived';

    use HasGeneratedID;
    use TrackCreate;
    use HasNotes;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_received;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_approved;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_filed;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="approver_id", referencedColumnName="id")
     */
    protected $approved_by;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="recommended_id", referencedColumnName="id")
     */
    protected $recommended_by;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="noted_id", referencedColumnName="id")
     */
    protected $noted_by;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\Department")
     * @ORM\JoinColumn(name="noted_id", referencedColumnName="id")
     */
    protected $department;

    /** @ORM\Column(type="string", length=20) */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\AdminBundle\Entity\JobTitle")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id")
     **/
    protected $position;

    /** @ORM\Column(type="text",nullable=true) */
    protected $gender;

    /** @ORM\Column(type="integer", length=5, nullable=true) */
    protected $age_from;

    /** @ORM\Column(type="integer", length=5, nullable=true) */
    protected $age_to;

    /** @ORM\Column(type="text",nullable=true) */
    protected $experience;

    /** @ORM\Column(type="text",nullable=true) */
    protected $education;

    /** @ORM\Column(type="text",nullable=true) */
    protected $required_courses;

    /** @ORM\Column(type="text",nullable=true) */
    protected $skills;

   /** @ORM\Column(type="text",nullable=true) */
    protected $terms_of_employment;

    /** @ORM\Column(type="text",nullable=true) */
    protected $purpose;

    /** @ORM\Column(type="text",nullable=true) */
    protected $personnel_type;

    /** @ORM\Column(type="string",length=50, nullable=true) */
    protected $internal_source_code;

    /** @ORM\Column(type="string",length=50, nullable=true) */
    protected $external_source_code;

    /** @ORM\Column(type="text",nullable=true) */
    protected $internal_candidates;

    /** @ORM\Column(type="text",nullable=true) */
    protected $external_candidates;

    /** @ORM\Column(type="integer", nullable = true) */
    protected $vacancy;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_DRAFT;
        $this->data = array();
        $this->date_approved = null;
        $this->date_received = null;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }

    public function setDateReceived($dateReceived)
    {
        $this->date_received = $dateReceived;

        return $this;
    }

    public function getDateReceived()
    {
        return $this->date_received;
    }

    public function setDateApproved($dateApproved)
    {
        $this->date_approved = $dateApproved;

        return $this;
    }

    public function getDateApproved()
    {
        return $this->date_approved;
    }

    public function setDateFiled($dateFiled)
    {
        $this->date_filed = $dateFiled;

        return $this;
    }

    public function getDateFiled()
    {
        if ($this->date_filed == null) {
            return $this->date_filed;
        } else {
            return $this->date_filed->format('F j, Y');
        }
        return $this->date_filed;
    }

    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setGender($gender)
    {
        $this->gender = serialize($gender);

        return $this;
    }

    public function getGender()
    {
        return unserialize($this->gender);
    }

    public function setAgeFrom($ageFrom)
    {
        $this->age_from = $ageFrom;

        return $this;
    }

    public function getAgeFrom()
    {
        return $this->age_from;
    }

    public function setAgeTo($ageTo)
    {
        $this->age_to = $ageTo;

        return $this;
    }

    public function getAgeTo()
    {
        return $this->age_to;
    }

    public function setExperience($experience)
    {
        $this->experience = serialize($experience);

        return $this;
    }

    public function getExperience()
    {
        return unserialize($this->experience);
    }

    public function setEducation($education)
    {
        $this->education = serialize($education);
        return $this;
    }

    public function getEducation()
    {
        return unserialize($this->education);
    }

    public function setRequiredCourses($requiredCourses)
    {
        $this->required_courses = serialize($requiredCourses);

        return $this;
    }

    public function getRequiredCourses()
    {
        return unserialize($this->required_courses);
    }

    public function setSkills($skills)
    {
        $this->skills = serialize($skills);

        return $this;
    }

    public function getSkills()
    {
        return unserialize($this->skills);
    }

    public function clearSkills()
    {
        $skills = array();
        $this->skills = serialize($skills);

        return $this;
    }

    public function setTermsOfEmployment($termsOfEmployment)
    {
        $this->terms_of_employment = serialize($termsOfEmployment);

        return $this;
    }

    public function getTermsOfEmployment()
    {
        return unserialize($this->terms_of_employment);
    }

    public function setPurpose($purpose)
    {
        $this->purpose = serialize($purpose);

        return $this;
    }

    public function getPurpose()
    {
        return unserialize($this->purpose);
    }

    public function setPersonnelType($personnelType)
    {
        $this->personnel_type = serialize($personnelType);

        return $this;
    }

    public function getPersonnelType()
    {
        return unserialize($this->personnel_type);
    }

    public function setInternalSourceCode($internalSourceCode)
    {
        $this->internal_source_code = $internalSourceCode;

        return $this;
    }

    public function getInternalSourceCode()
    {
        return $this->internal_source_code;
    }

    public function setExternalSourceCode($externalSourceCode)
    {
        $this->external_source_code = $externalSourceCode;

        return $this;
    }

    public function getExternalSourceCode()
    {
        return $this->external_source_code;
    }

    public function setInternalCandidates($internalCandidates)
    {
        $this->internal_candidates = serialize($internalCandidates);

        return $this;
    }

    public function getInternalCandidates()
    {
        return unserialize($this->internal_candidates);
    }

    public function setExternalCandidates($externalCandidates)
    {
        $this->external_candidates = serialize($externalCandidates);

        return $this;
    }

    public function getExternalCandidates()
    {
        return unserialize($this->external_candidates);
    }

    public function setApprovedBy(\Hris\WorkforceBundle\Entity\Employee $approvedBy = null)
    {
        $this->approved_by = $approvedBy;

        return $this;
    }

    public function getApprovedBy()
    {
        return $this->approved_by;
    }

    public function setRecommendedBy(\Hris\WorkforceBundle\Entity\Employee $recommendedBy = null)
    {
        $this->recommended_by = $recommendedBy;

        return $this;
    }

    public function getRecommendedBy()
    {
        return $this->recommended_by;
    }

    public function setNotedBy(\Hris\WorkforceBundle\Entity\Employee $notedBy = null)
    {
        $this->noted_by = $notedBy;

        return $this;
    }

    public function getNotedBy()
    {
        return $this->noted_by;
    }

    public function setDepartment(\Hris\AdminBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    public function getDepartment()
    {
        return $this->department;
    }

    public function setPosition(\Hris\AdminBundle\Entity\JobTitle $position = null)
    {
        $this->position = $position;

        return $this;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setVacancy($number)
    {
        $this->vacancy = $number;
        return $this;
    }

    public function getVacancy()
    {
        return $this->vacancy;
    }
}
