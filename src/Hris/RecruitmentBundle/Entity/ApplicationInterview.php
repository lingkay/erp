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
 * @ORM\Table(name="hr_app_interview")
 */
class ApplicationInterview
{
    use HasGeneratedID;
    use TrackCreate;

    /** 
     * @ORM\ManyToOne(targetEntity="Application", inversedBy="interview")
     * @ORM\JoinColumn(name="application_id", referencedColumnName="id")
     */
    protected $app_id;

    /** @ORM\Column (type="datetime", nullable = true) */
    protected $date;

    /** @ORM\Column (type="datetime", nullable = true) */
    protected $time;

    /** @ORM\Column (type="json_array", nullable = true) */
    protected $interview_result;

    /** @ORM\Column(type="string", length = 10, nullable = true) */
    protected $status;

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

    public function setEmployee($emp)
    {
        $this->employee = $emp;
        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }
    
    public function setDate(DateTime $date)
    {
        $this->date = $date;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setTime(DateTime $time)
    {
        $this->time = $time;
        return $this;
    }

    public function getTime()
    {
        if($this->time == null) {
            return $this->time;
        }
        else {
            return $this->time->format('g:i A');
        }
    }

    public function setResult($result)
    {
        $this->interview_result = $result;
        return $this;
    }

    public function getResult()
    {
        return $this->interview_result;
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

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        return $data;
    }
}
?>