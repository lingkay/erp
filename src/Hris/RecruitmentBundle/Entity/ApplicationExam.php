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
 * @ORM\Table(name="hr_app_exam")
 */
class ApplicationExam
{
	use HasGeneratedID;
	use TrackCreate;

	/** 
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="exam")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** @ORM\Column (type="datetime", nullable = true) */
    protected $exam_date;

    /** @ORM\Column (type="datetime", nullable = true) */
    protected $exam_time;

    /** @ORM\Column(type="json_array", nullable=true) */
    protected $exam_result;

    /** @ORM\Column(type="string", length=20, nullable=true) */
    protected $result;

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

    public function setDate(DateTime $date)
    {
        $this->exam_date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->exam_date;
    }

    public function setTime(DateTime $time)
    {
        $this->exam_time = $time;
        return $this;
    }

    public function getTime()
    {
        if($this->exam_time == null) {
            return $this->exam_time;
        }
        else {
            return $this->exam_time->format('g:i A');
        }
    }

    public function setResult($result)
    {
        $this->exam_result = $result;
        return $this;
    }

    public function getResult()
    {
        return $this->exam_result;
    }

    public function setStatus($status)
    {
        $this->result = $status;
        return $this;
    }

    public function getStatus()
    {
        return $this->result;
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