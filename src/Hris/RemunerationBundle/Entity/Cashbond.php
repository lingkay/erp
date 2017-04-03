<?php

namespace Hris\RemunerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;


use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="rem_cashbond")
 */

class Cashbond
{
    const STATUS_ACTIVE = 'Active';
    const STATUS_INACTIVE = 'Inactive';

	use HasGeneratedID;
    use HasCode;
	use TrackCreate;

	/**
	 * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
	 * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
	 */
	protected $employee;


    /** @ORM\OneToMany(targetEntity="CashbondTransaction", mappedBy="cashbond") */
    protected $transactions;

    /** @ORM\OneToMany(targetEntity="CashbondLoan", mappedBy="cashbond") */
    protected $loan;

    /** @ORM\Column(type="string", nullable=true) */
    protected $status;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $balance;


	public function __construct($employee = null)
    {
        $this->employee = $employee;
        $this->initTrackCreate();
        $this->transactions = new ArrayCollection();
        $this->balance = 0.0;
    }

    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee)
    {
    	$this->employee = $employee;
    	return $this;
    }

    public function getEmployee()
    {
    	return $this->employee;
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

    public function setStatus($status)
    {
        $this->status = $statustus;
        return $this;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function addTransaction(\Hris\RemunerationBundle\Entity\CashbondTransaction $transaction)
    {
        $transaction->setCashbond($this);
        $this->transactions->add($transaction);
        $this->balance += $transaction->getDebit();
        $this->balance -= $transaction->getCredit();

        //Saves the running balance of the employee
        $transaction->setBalance($this->balance);
        return $this;
    }

    public function getTransactions()
    {
        return $this->transactions;
    }

    public function getContributions()
    {
        return $this->transactions->filter(
            function($entry) {
               return $entry->getType() == CashbondTransaction::TYPE_CONTRIBUTION;
            });
    }

    public function addLoan(\Hris\RemunerationBundle\Entity\CashbondLoan $loan)
    {
        $loan->setCashbond($this);
        $this->loan->add($loan);
        return $this;
    }

    public function getLoans()
    {
        return $this->loan;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->emp_name = $this->employee->getDisplayName();
        $data->emp_id = $this->employee->getID();
        $data->balance = $this->balance;
        foreach($this->transactions as $transaction)
        {
            $data->transactions[] = $transaction->toData();
        }
        
        return $data;
    }
}
?>