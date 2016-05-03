<?php
namespace Hris\RecruitmentBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use Hris\RecruitmentBundle\Entity\Application;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_app_education")
 */
class ApplicationEducation
{
    use HasGeneratedID;
    use TrackCreate;

    /** 
     * @ORM\OneToOne(targetEntity="Application", inversedBy="education")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** @ORM\Column(type="json_array") */
    protected $elementary;

    /** @ORM\Column(type="json_array") */
    protected $highschool;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $vocational;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $college;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $post_graduate;

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

    public function setElementary($data)
    {
        $this->elementary = $data;
        return $this;
    }

    public function getElementary()
    {
        return $this->elementary;
    }

    public function setHighschool($data)
    {
        $this->highschool = $data;
        return $this;
    }

    public function getHighschool()
    {
        return $this->highschool;
    }

    public function setVocational($data)
    {
        $this->vocational = $data;
        return $this;
    }

    public function getVocational()
    {
        return $this->vocational;
    }

    public function setCollege($data)
    {
        $this->college = $data;
        return $this;
    }

    public function getCollege()
    {
        return $this->college;
    }

    public function setPostgraduate($data)
    {
        $this->post_graduate = $data;
        return $this;
    }

    public function getPostgraduate()
    {
        return $this->post_graduate;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }
}