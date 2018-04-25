<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="payroll_settings_fines")
 */
class Fines
{
    use HasGeneratedID;
    use HasName;

    /**
     * @ORM\ManyToOne(targetEntity="FineTypes")
     * @ORM\JoinColumn(name="type", referencedColumnName="id")
     */
    protected $type;

    /**
     * @ORM\ManyToOne(targetEntity="FineValueTypes")
     * @ORM\JoinColumn(name="value_type", referencedColumnName="id")
     */
    protected $valueType;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $amount;

    /** @ORM\Column(type="string", length=200, nullable=true) */
    protected $formula;

    public function __construct()
    {
        $this->initHasName();
    }

    /**
     * Set type
     *
     * @param \Hris\AdminBundle\Entity\FineValueTypes $type
     *
     * @return Fines
     */
    public function setValueType(\Hris\AdminBundle\Entity\FineValueTypes $type = null)
    {
        $this->valueType = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Hris\AdminBundle\Entity\FineValueTypes
     */
    public function getValueType()
    {
        return $this->valueType;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);
        return $data;
    }

    /**
     * Set automatic
     *
     * @param boolean $automatic
     *
     * @return Fines
     */
    public function setAutomatic($automatic)
    {
        $this->automatic = $automatic;

        return $this;
    }

    /**
     * Get automatic
     *
     * @return boolean
     */
    public function getAutomatic()
    {
        return $this->automatic;
    }

    /**
     * Set amount
     *
     * @param string $amount
     *
     * @return Fines
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

    /**
     * Set formula
     *
     * @param string $formula
     *
     * @return Fines
     */
    public function setFormula($formula)
    {
        $this->formula = $formula;

        return $this;
    }

    /**
     * Get formula
     *
     * @return string
     */
    public function getFormula()
    {
        return $this->formula;
    }

    /**
     * Set type
     *
     * @param \Hris\AdminBundle\Entity\FineTypes $type
     *
     * @return Fines
     */
    public function setType(\Hris\AdminBundle\Entity\FineTypes $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Hris\AdminBundle\Entity\FineTypes
     */
    public function getType()
    {
        return $this->type;
    }
}
