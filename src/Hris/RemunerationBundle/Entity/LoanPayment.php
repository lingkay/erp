<?php

namespace Hris\RemunerationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\TrackCreate;

Use Hris\RemunerationBundle\Entity\Loan;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="rem_loan_payments")
 */

class LoanPayment
{
    use HasGeneratedID;
    use HasCode;
	use TrackCreate;

    /**
     * @ORM\ManyToOne(targetEntity="\Hris\RemunerationBundle\Entity\Loan")
     * @ORM\JoinColumn(name="loan_id", referencedColumnName="id")
     */
    protected $loan;

	/** @ORM\Column(type="datetime") */
	protected $date_paid;

    /** @ORM\Column(type="decimal",precision=10, scale=2) */
    protected $payment;

	public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setLoan(Loan $loan)
    {
        $this->loan = $loan;
        return $this;
    }

    public function getLoan()
    {
        return $this->loan;
    }

    public function setDatePaid(DateTime $date)
    {
        $this->date_paid = $date;
        return $this;
    }

    public function getDatePaid()
    {
        return $this->date_paid;
    }

    public function getDatePaidDisplay()
    {
        return $this->date_paid->format('m/d/Y');
    }

 

    public function setPayment($payment)
    {
        $this->payment = $payment;
        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }



    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasCode($data);

        $data->payment = $this->payment;
        $data->date_paid = $this->getDatePaidDisplay();
        return $data;
    }
}
?>