<?php

namespace Hris\RemunerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Hris\RemunerationBundle\Entity\Cashbond;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="rem_cashbondtransaction")
 */

class CashbondTransaction
{
    const TYPE_CONTRIBUTION = 'Contribution';
    const TYPE_LOAN = 'Loan';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\RemunerationBundle\Entity\Cashbond")
	 * @ORM\JoinColumn(name="cashbond_id", referencedColumnName="id")
	 */
	protected $cashbond;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\PayrollBundle\Entity\PayPayrollPeriod")
     * @ORM\JoinColumn(name="payroll_period_id", referencedColumnName="id")
     */
    protected $payroll_period;

    /** @ORM\Column(type="string", nullable=true) */
    protected $type;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $debit;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $credit;


    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $balance;


	public function __construct()
    {
        $this->initTrackCreate();
        $this->transactions = new ArrayCollection();
        $this->debit = 0.0;
        $this->credit = 0.0;
        $this->balance = 0.0;
    }

    public function setCashbond(Cashbond $cashbond)
    {
    	$this->cashbond = $cashbond;
    	return $this;
    }

    public function getCashbond()
    {
    	return $this->cashbond;
    }

    public function setDebit($debit)
    {
        $this->debit = $debit;
        return $this;
    }

    public function getDebit()
    {
        return $this->debit;
    }

    public function setCredit($credit)
    {
        $this->credit = $credit;
        return $this;
    }

    public function getCredit()
    {
        return $this->credit;
    }


    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setLoan($amount)
    {
        $this->debit = 0.0;
        $this->credit = $amount;
        $this->type = self::TYPE_LOAN;
        return $this;
    }

    public function setContribution($amount)
    {
        $this->credit = 0.0;
        $this->debit = $amount;
        $this->type = self::TYPE_CONTRIBUTION;
        return $this;
    }

    public function setPayrollPeriod(\Hris\PayrollBundle\Entity\PayPayrollPeriod $period)
    {
        $this->payroll_period = $period;
        return $this;
    }

    public function getPayrollPeriod()
    {
        return $this->payroll_period;
    }

    public function setBalance($balance)
    {
        $this->balance = $balance;
        return $this;
    }

    public function getBalance()
    {
        return $this->balance;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $data->debit = $this->debit;
        $data->credit = $this->credit;
        $data->type = $this->type;
        return $data;
    }
}
?>