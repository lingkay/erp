<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasNotes;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_incident_report")
 */

class IncidentReport
{
    const STATUS_PENDING    =   'Pending';
    const STATUS_REVIEWED   =   'Reviewed';

	use HasGeneratedID;
	use TrackCreate;
    use HasNotes;

	/**
	 * @ORM\ManyToOne(targetEntity="Catalyst\UserBundle\Entity\User")
	 * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
	 */
	protected $reporter;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Department")
     * @ORM\JoinColumn(name="dept_id", referencedColumnName="id")
     */
    protected $department;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\AdminBundle\Entity\Location")
     * @ORM\JoinColumn(name="location_id", referencedColumnName="id")
     */
    protected $location;

	/** @ORM\Column(type="datetime") */
	protected $date_filed;

    /** @ORM\Column(type="datetime") */
    protected $date_happened;

    /** @ORM\Column(type="text", nullable=true) */
    protected $products;

    /** @ORM\Column(type="text", nullable=true) */
    protected $concerns;

    /** @ORM\Column(type="string", nullable=true) */
    protected $action;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

	public function __construct()
    {
        $this->initTrackCreate();
        $this->status = self::STATUS_PENDING;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasNotes($data);

        // $data->reporter_name = $this->reporter->getDisplayName();
        // $data->reporter_id = $this->reporter->getID();
        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->dept_name = $this->department->getName();
        $data->dept_id = $this->department->getID();
        $data->loc_name = $this->location->getName();
        $data->loc_id = $this->location->getID();
        $data->date_filed = $this->date_filed;
        $data->date_happened = $this->date_happened;
        $data->products = $this->products;
        $data->concerns = $this->concerns;
        return $data;
    }

    /**
     * Set dateFiled
     *
     * @param \DateTime $dateFiled
     *
     * @return IncidentReport
     */
    public function setDateFiled($dateFiled)
    {
        $this->date_filed = $dateFiled;

        return $this;
    }

    /**
     * Get dateFiled
     *
     * @return \DateTime
     */
    public function getDateFiled()
    {
        return $this->date_filed;
    }

    /**
     * Set products
     *
     * @param string $products
     *
     * @return IncidentReport
     */
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }

    /**
     * Get products
     *
     * @return string
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set concerns
     *
     * @param string $concerns
     *
     * @return IncidentReport
     */
    public function setConcerns($concerns)
    {
        $this->concerns = $concerns;

        return $this;
    }

    /**
     * Get concerns
     *
     * @return string
     */
    public function getConcerns()
    {
        return $this->concerns;
    }

    /**
     * Set department
     *
     * @param \Hris\AdminBundle\Entity\Department $department
     *
     * @return IncidentReport
     */
    public function setDepartment(\Hris\AdminBundle\Entity\Department $department = null)
    {
        $this->department = $department;

        return $this;
    }

    /**
     * Get department
     *
     * @return \Hris\AdminBundle\Entity\Department
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return IncidentReport
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
     * Set location
     *
     * @param \Hris\AdminBundle\Entity\Location $location
     *
     * @return IncidentReport
     */
    public function setLocation(\Hris\AdminBundle\Entity\Location $location = null)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * Get location
     *
     * @return \Hris\AdminBundle\Entity\Location
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * Set dateHappened
     *
     * @param \DateTime $dateHappened
     *
     * @return IncidentReport
     */
    public function setDateHappened($dateHappened)
    {
        $this->date_happened = $dateHappened;

        return $this;
    }

    /**
     * Get dateHappened
     *
     * @return \DateTime
     */
    public function getDateHappened()
    {
        return $this->date_happened;
    }

    /**
     * Set reporter
     *
     * @param \Catalyst\UserBundle\Entity\User $reporter
     *
     * @return IncidentReport
     */
    public function setReporter(\Catalyst\UserBundle\Entity\User $reporter = null)
    {
        $this->reporter = $reporter;

        return $this;
    }

    /**
     * Get reporter
     *
     * @return \Catalyst\UserBundle\Entity\User
     */
    public function getReporter()
    {
        return $this->reporter;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return IncidentReport
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
     * Set action
     *
     * @param string $action
     *
     * @return IncidentReport
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }
}
