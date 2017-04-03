<?php 
namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use Hris\RecruitmentBundle\Entity\Application;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_other_info")
 */
class ApplicationInformation
{
	use HasGeneratedID;
	use TrackCreate;

	/** 
     * @ORM\OneToOne(targetEntity="Application", inversedBy="information")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** @ORM\Column(type="boolean") */
    protected $forced_resign;

    /** @ORM\Column(type="boolean") */
    protected $crime_convicted;

    /** @ORM\Column(type="boolean") */
    protected $serious_disease;

    /** @ORM\Column(type="boolean") */
    protected $license;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $license_type;

    /** @ORM\Column(type="string") */
    protected $sss_number;

    /** @ORM\Column(type="string") */
    protected $tin_number;

    /** @ORM\Column(type="string") */
    protected $philhealth_number;

    /** @ORM\Column(type="string") */
    protected $pagibig_number;

    /** @ORM\Column(type="json_array") */
    protected $data;

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

    public function setForcedResign($resign)
    {
    	$this->forced_resign = $resign;
    	return $this;
    }

    public function getForcedResign()
    {
    	return $this->forced_resign;
    }

    public function setCrimeConvicted($crime)
    {
    	$this->crime_convicted = $crime;
    	return $this;
    }

    public function getCrimeConvicted()
    {
    	return $this->crime_convicted;
    }

    public function setSeriousDisease($disease)
    {
    	$this->serious_disease = $disease;
    	return $this;
    }

    public function getSeriousDisease()
    {
    	return $this->serious_disease;
    }

    public function setLicense($license)
    {
    	$this->license = $license;
    	return $this;
    }

    public function getLicense()
    {
    	return $this->license;
    }

    public function setLicenseType($type)
    {
    	$this->license_type = $type;
    	return $this;
    }

    public function getLicenseType()
    {
    	return $this->license_type;
    }

    public function setSSSNumber($number)
    {
        $this->sss_number = $number;
        return $this;
    }

    public function getSSSNumber()
    {
        return $this->sss_number;
    }

    public function setTinNumber($number)
    {
        $this->tin_number = $number;
        return $this;
    }

    public function getTinNumber()
    {
        return $this->tin_number;
    }

    public function setPhilHealthNumber($number)
    {
        $this->philhealth_number = $number;
        return $this;
    }

    public function getPhilHealthNumber()
    {
        return $this->philhealth_number;
    }

    public function setPagIbigNumber($number)
    {
        $this->pagibig_number = $number;
        return $this;
    }

    public function getPagIbigNumber()
    {
        return $this->pagibig_number;
    }

    public function setData($data)
    {
        $this->data = $data;
        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }
}
?>