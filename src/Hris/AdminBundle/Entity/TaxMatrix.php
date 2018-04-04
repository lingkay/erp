<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_tax_matrix")
 */
class TaxMatrix
{
    use HasGeneratedID;
    use HasName;

    /** @ORM\Column(type="string", nullable=true)*/
    protected $bracket;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_from;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount_to;

    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $amount_tax;

    public function __construct()
    {
        $this->initHasName();
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

    public function getTaxFormatted()
    {
        return $this->amount_tax. '%';
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
        $this->dataHasName($data);
        return $data;
    }
}
