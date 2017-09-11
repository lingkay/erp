<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_payroll_period")
 */
class PayPayrollPeriod
{
	use HasGeneratedID;

	/**
	 * @ORM\ManytoOne(targetEntity="Hris\PayrollBundle\Entity\PayPeriod")
	 * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
	 */
	protected $period;

	/** @ORM\Column(type="datetime") */
	protected $start_date;

	/** @ORM\Column(type="datetime") */
	protected $end_date;

	/** @ORM\Column(type="string", length=5) */
	protected $fs_month;

	/** @ORM\Column(type="string", length=5) */
	protected $fs_year;

	public function __construct()
	{
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

	public function setStartDate(DateTime $date)
	{
		$this->start_date = $date;
		return $this;
	}

	public function getStartDate()
	{
		
	    return $this->start_date; 
		
	}

	public function setEndDate(DateTime $date)
	{
		$this->end_date = $date;
		return $this;
	}

	public function getEndDate()
	{

		return $this->end_date;
	}

	public function setFiscalMonth($month)
	{
		$this->fs_month = $month;
		return $this;
	}

	public function getFiscalMonth()
	{
		return $this->fs_month;
	}

	public function setFiscalYear( $year)
	{
		$this->fs_year = $year;
		return $this;
	}

	public function getFiscalYear()
	{
		return $this->fs_year;
	}

	public function getPayPeriodFormatted()
	{
		return  $this->start_date->format('m/d/Y').' - '.$this->end_date->format('m/d/Y');
	}

	  public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $data->start_date = $this->start_date->format('m-d-Y');
        $data->end_date = $this->end_date->format('m-d-Y');

        return $data;
    }
}