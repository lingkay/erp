<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_tax_rate")
 */
class PayTaxRate
{
    use HasGeneratedID;

    /** @ORM\Column(type="string")*/
    protected $bracket;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $amount_from;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $amount_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $amount_tax;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $percent_of_excess;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\PayrollBundle\Entity\PayTaxStatus")
     * @ORM\JoinColumn(name="taxstatus_id", referencedColumnName="id")
     */
    protected $status;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\PayrollBundle\Entity\PayPeriod")
     * @ORM\JoinColumn(name="period_id", referencedColumnName="id")
     */
    protected $period;

    public function __construct()
    {
    }
    
    public function setBracket($bracket)
    {
        $this->bracket = $bracket;
        return $this;
    }

    public function getBracket()
    {
        return $this->bracket;
    }

    public function setMinimum($amt_from)
    {
        $this->amount_from = $amt_from;
        return $this;
    }

    public function getMinimum()
    {
        return $this->amount_from;
    }

    public function setMaximum($amt_to)
    {
        $this->amount_to = $amt_to;
        return $this;
    }

    public function getMaximum()
    {
        return $this->amount_to;
    }

    public function setTax($amt_tax)
    {
        $this->amount_tax = $amt_tax;
        return $this;
    }

    public function getTax()
    {
        return $this->amount_tax;
    }

    public function setExcess($excess)
    {
        $this->percent_of_excess = $excess;
        return $this;
    }

    public function getExcess()
    {
        return $this->percent_of_excess;
    }

    public function setPeriod(\Hris\PayrollBundle\Entity\PayPeriod $period)
    {
        $this->period = $period;
        return $this;
    }

    public function getPeriod()
    {
        return $this->period;
    }

    public function setTaxStatus(\Hris\PayrollBundle\Entity\PayTaxStatus $status)
    {
        $this->status = $status;
        return $this;
    }

    public function getTaxStatus()
    {
        return $this->status;
    }
    
    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasCode($data);

        return $data;
    }
}
