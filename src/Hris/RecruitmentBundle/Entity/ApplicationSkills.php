<?php 
namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\CoreBundle\Template\Entity\HasName;

use Hris\RecruitmentBundle\Entity\Application;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_skills")
 */
class ApplicationSkills
{
	use HasGeneratedID;
	use TrackCreate;

	/** 
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="skills")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

        /**
     * @ORM\Column(type="json_array")
     */
    protected $computer;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $related;

    /**
     * @ORM\Column(type="json_array")
     */
    protected $hobbies;

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

    public function setRelated($skills)
    {
        $this->related = $skills;
        return $this;
    }

    public function getRelated()
    {
        return $this->related;
    }

    public function setHobbies($hobbies)
    {
        $this->hobbies = $hobbies;
        return $this;
    }

    public function getHobbies()
    {
        return $this->hobbies;
    }
    public function setComputer($skills)
    {
        $this->computer = $skills;
        return $this;
    }
    public function getComputer()
    {
        return $this->computer;
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