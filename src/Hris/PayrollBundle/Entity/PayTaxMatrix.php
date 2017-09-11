<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_taxmatrix")
 */
class PayTaxMatrix
{
	use HasGeneratedID;

	/** 
	 * @ORM\OneToOne(targetEntity="Hris\PayrollBundle\Entity\PayTaxRate")
	 * @ORM\JoinColumn(name="taxrate_id", referencedColumnName="id")
	 */
	protected $rate_id;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\PayrollBundle\Entity\PayTaxStatus")
	 * @ORM\JoinColumn(name="taxstatus_id", referencedColumnName="id")
	 */
	protected $status_id;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\PayrollBundle\Entity\PayPeriod")
	 * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
	 */
	protected $period;

	/** @ORM\Column(type="decimal", precision = 10, scale = 2)*/
	protected $base_amount;

	public function __construct()
	{
	}

	public function setTaxRate(\Hris\PayrollBundle\Entity\PayTaxRate $rate)
	{
		$this->rate_id = $rate;
		return $this;
	}

	public function getTaxRate()
	{
		return $this->rate_id;
	}

	public function setTaxStatus(\Hris\PayrollBundle\Entity\PayTaxStatus $status)
	{
		$this->status_id = $status;
		return $this;
	}

	public function getTaxStatus()
	{
		return $this->status_id;
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

	public function setBaseAmount($base_amount)
	{
		$this->base_amount = $base_amount;
		return $this;
	}

	public function getBaseAmount()
	{
		return $this->base_amount;
	}

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);

        return $data;
    }
}