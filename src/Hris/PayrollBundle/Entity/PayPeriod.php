<?php

namespace Hris\PayrollBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;


use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_period")
 */
class PayPeriod
{
    use HasGeneratedID;
    use HasName;

    const TYPE_DAILY = "Daily";
    const TYPE_WEEKLY = "Weekly";
    const TYPE_BIWEEKLY = "Bi-Weekly";
    const TYPE_SEMIMONTHLY = "Semi-Monthly";
    const TYPE_MONTHLY = "Monthly";
    const TYPE_QUARTERLY = "Quarterly";
    const TYPE_BIANNUAL = "Bi-Annual";
    const TYPE_ANNUAL = "Annual";


    /** @ORM\Column(type="integer") */
    protected $paydays;


    public function __construct()
    {
    }

    public function setPaydays($paydays)
    {
        $this->paydays = $paydays;
        return $this;
    }

    public function getPaydays()
    {
        return $this->paydays;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);

        return $data;
    }
}
