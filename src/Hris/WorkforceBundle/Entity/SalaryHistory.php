<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;
use Catalyst\MediaBundle\Template\Entity\HasUpload;

use stdClass;

/**
 * @ORM\Entity(repositoryClass="Hris\WorkforceBundle\Entity\Repository\SalaryHistoryRepository")
 * @ORM\Table(name="hr_salary_history")
 */
class SalaryHistory
{
    use HasGeneratedID;
    use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee", inversedBy="salary_history")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $base_pay;

    /**
     * @ORM\ManytoOne(targetEntity="Hris\PayrollBundle\Entity\PayPeriod")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     */
    protected $period;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    public function getEmployee()
    {
        return $this->employee;
    }

    public function getEmployeeName()
    {
        return $this->employee->getDisplayName();
    }

    public function setPay($pay)
    {
        $this->base_pay = $pay;
        return $this;
    }

    public function getPay()
    {
        return $this->base_pay;
    }

    public function setPayPeriod(\Hris\PayrollBundle\Entity\PayPeriod $period)
    {
        $this->period = $period;
        return $this;
    }

    public function getPayPeriod()
    {
        return $this->period;
    }

   
}
