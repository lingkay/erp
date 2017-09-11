<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_evaluator")
 */
class Evaluator
{
    use HasGeneratedID;
    use TrackCreate;

    // CONSTANT FOR STATUS
    const PENDING = 'Pending';
    const COMPLETED = 'Completed';
    const APPROVED = 'Approved';

    // CONSTANT FOR NO GRADE
    const INCOMPLETE = 'N/A';

    /**
     * @ORM\ManyToOne(targetEntity="Appraisal")
     * @ORM\JoinColumn(name="appraisal_id", referencedColumnName="id")
     */
    protected $appraisal;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=80) */
    protected $status;

    /** @ORM\Column(type="string", length=80) */
    protected $quali_rating;

    /** @ORM\Column(type="string", length=80) */
    protected $quanti_rating;

    /** @ORM\Column(type="text", nullable=true) */
    protected $kpi_details;

    /** @ORM\Column(type="text", nullable=true) */
    protected $pqc_details;

    /** @ORM\Column(type="text", nullable=true) */
    protected $comments;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_evaluated;

    /**
     * Constructor
     */
    public function __construct()
    {
        // $this->evaluations = new \Doctrine\Common\Collections\ArrayCollection();
        $this->initTrackCreate();
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return Evaluator
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
     * Set appraisal
     *
     * @param \Hris\WorkforceBundle\Entity\Appraisal $appraisal
     *
     * @return Evaluator
     */
    public function setAppraisal(\Hris\WorkforceBundle\Entity\Appraisal $appraisal = null)
    {
        $this->appraisal = $appraisal;

        return $this;
    }

    /**
     * Get appraisal
     *
     * @return \Hris\WorkforceBundle\Entity\Appraisal
     */
    public function getAppraisal()
    {
        return $this->appraisal;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Evaluator
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set qualiRating
     *
     * @param string $qualiRating
     *
     * @return Evaluator
     */
    public function setQualiRating($qualiRating)
    {
        $this->quali_rating = $qualiRating;

        return $this;
    }

    /**
     * Get qualiRating
     *
     * @return string
     */
    public function getQualiRating()
    {
        return $this->quali_rating;
    }

    /**
     * Set quantiRating
     *
     * @param string $quantiRating
     *
     * @return Evaluator
     */
    public function setQuantiRating($quantiRating)
    {
        $this->quanti_rating = $quantiRating;

        return $this;
    }

    /**
     * Get quantiRating
     *
     * @return string
     */
    public function getQuantiRating()
    {
        return $this->quanti_rating;
    }

    /**
     * Set kpiDetails
     *
     * @param string $kpiDetails
     *
     * @return Evaluator
     */
    public function setKPIDetails($kpiDetails)
    {
        $this->kpi_details = serialize($kpiDetails);

        return $this;
    }

    /**
     * Get kpiDetails
     *
     * @return string
     */
    public function getKPIDetails()
    {
        return unserialize($this->kpi_details);
    }

    /**
     * Set pqcDetails
     *
     * @param string $pqcDetails
     *
     * @return Evaluator
     */
    public function setPQCDetails($pqcDetails)
    {
        $this->pqc_details = serialize($pqcDetails);

        return $this;
    }

    /**
     * Get pqcDetails
     *
     * @return string
     */
    public function getPQCDetails()
    {
        return unserialize($this->pqc_details);
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Evaluator
     */
    public function setComments($comments)
    {
        $this->comments = serialize($comments);

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return unserialize($this->comments);
    }

    /**
     * Set dateEvaluated
     *
     * @param \DateTime $dateEvaluated
     *
     * @return Evaluator
     */
    public function setDateEvaluated($dateEvaluated)
    {
        $this->date_evaluated = $dateEvaluated;

        return $this;
    }

    /**
     * Get dateEvaluated
     *
     * @return \DateTime
     */
    public function getDateEvaluated()
    {
        return $this->date_evaluated;
    }
}
