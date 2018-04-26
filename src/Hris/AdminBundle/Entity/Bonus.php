<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="payroll_settings_bonus")
 */
class Bonus
{
    use HasGeneratedID;
    use HasName;
    
    /**
     * @ORM\ManyToOne(targetEntity="BonusTypes")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    protected $type;

    /** @ORM\Column(type="string", length=150, nullable=true) */
    protected $authorized_giver;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount;

    public function __construct()
    {
        $this->initHasName();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);
        return $data;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Bonus
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return string
     */
    public function getAmount()
    {
        return $this->amount;
    }

    public function setAuthorizedGiver($authorizedGiver = null)
    {
        $this->authorized_giver = $authorizedGiver;

        return $this;
    }

    public function getAuthorizedGiver()
    {
        return $this->authorized_giver;
    }

    /**
     * Set type
     *
     * @param \Hris\AdminBundle\Entity\BonusTypes $type
     *
     * @return Bonus
     */
    public function setType(\Hris\AdminBundle\Entity\BonusTypes $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Hris\AdminBundle\Entity\BonusTypes
     */
    public function getType()
    {
        return $this->type;
    }
}
