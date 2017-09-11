<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_appraisal")
 */
class Appraisal
{
    const REGULARIZATION = 'Regularization';
    const PROMOTION = 'Promotion';
    const QUARTER = 'Quarterly Review';
    const MERIT = 'Merit/Salary Increase';
    const OTHERS = 'Others';

    const COMPLETE = 'Complete';
    const INCOMPLETE = 'On-going';

    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\AppraisalSettings")
     * @ORM\JoinColumn(name="preset_id", referencedColumnName="id")
     */
    protected $preset;

    /** @ORM\Column(type="datetime") */
    protected $date_start;

    /** @ORM\Column(type="datetime") */
    protected $date_end;

    /** @ORM\Column(type="string", length=20) */
    protected $overall_quali;

    /** @ORM\Column(type="string", length=10) */
    protected $overall_quanti;

    /** @ORM\Column(type="string", length=30) */
    protected $type;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function getEmployeeName()
    {
        return $this->employee->getDisplayName();
    }

    public function getEvalPeriod()
    {
        return $this->getDateStart()->format('m/d/Y').' - '.$this->getDateEnd()->format('m/d/Y');
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);

        return $data;
    }

    /**
     * Set dateStart
     *
     * @param \DateTime $dateStart
     *
     * @return Appraisal
     */
    public function setDateStart($dateStart)
    {
        $this->date_start = $dateStart;

        return $this;
    }

    /**
     * Get dateStart
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * Set dateEnd
     *
     * @param \DateTime $dateEnd
     *
     * @return Appraisal
     */
    public function setDateEnd($dateEnd)
    {
        $this->date_end = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }


    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return Appraisal
     */
    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \Hris\WorkforceBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Appraisal
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set preset
     *
     * @param \Hris\AdminBundle\Entity\AppraisalSettings $preset
     *
     * @return Appraisal
     */
    public function setPreset(\Hris\AdminBundle\Entity\AppraisalSettings $preset = null)
    {
        $this->preset = $preset;

        return $this;
    }

    /**
     * Get preset
     *
     * @return \Hris\AdminBundle\Entity\AppraisalSettings
     */
    public function getPreset()
    {
        return $this->preset;
    }

    /**
     * Set overallQuali
     *
     * @param string $overallQuali
     *
     * @return Appraisal
     */
    public function setOverallQuali($overallQuali)
    {
        $this->overall_quali = $overallQuali;

        return $this;
    }

    /**
     * Get overallQuali
     *
     * @return string
     */
    public function getOverallQuali()
    {
        return $this->overall_quali;
    }

    /**
     * Set overallQuanti
     *
     * @param string $overallQuanti
     *
     * @return Appraisal
     */
    public function setOverallQuanti($overallQuanti)
    {
        $this->overall_quanti = $overallQuanti;

        return $this;
    }

    /**
     * Get overallQuanti
     *
     * @return string
     */
    public function getOverallQuanti()
    {
        return $this->overall_quanti;
    }
}
