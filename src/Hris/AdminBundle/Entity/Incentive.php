<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="payroll_settings_incentive")
 */
class Incentive
{
    use HasGeneratedID;
    use HasName;

    /**
     * @ORM\OneToMany(targetEntity="IncentiveMatrix", mappedBy="incentive", cascade={"persist"})
     */
    protected $entries;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $daily_sales;

    /** @ORM\Column(type="decimal", precision=10, scale=2, nullable=true) */
    protected $target;

    /** @ORM\Column(type="string", length=200, nullable=true) */
    protected $color;

    /**
     * @ORM\ManyToOne(targetEntity="Gist\UserBundle\Entity\Group")
     * @ORM\JoinColumn(name="position_id", referencedColumnName="id", unique=true)
     */
    protected $position;

    /**
     * @ORM\ManyToOne(targetEntity="IncentivePeriod")
     * @ORM\JoinColumn(name="period", referencedColumnName="id")
     */
    protected $period;

    public function __construct()
    {
        $this->initHasName();
    }

    /**
     * Set type
     *
     * @param \Hris\AdminBundle\Entity\IncentivePeriod $type
     *
     * @return Incentive
     */
    public function setPeriod(\Hris\AdminBundle\Entity\IncentivePeriod $type = null)
    {
        $this->period = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Hris\AdminBundle\Entity\IncentivePeriod
     */
    public function getPeriod()
    {
        return $this->period;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);
        return $data;
    }

    public function setGroup($group)
    {
        $this->position = $group;
        return $this;
    }

    public function getGroup()
    {
        return $this->position;
    }

    public function getPositionName()
    {
        return $this->position->getName();
    }

    /**
     * Set dailySales
     *
     * @param string $dailySales
     *
     * @return Incentive
     */
    public function setDailySales($dailySales)
    {
        $this->daily_sales = $dailySales;

        return $this;
    }

    /**
     * Get dailySales
     *
     * @return string
     */
    public function getDailySales()
    {
        return $this->daily_sales;
    }

    /**
     * Set target
     *
     * @param string $target
     *
     * @return Incentive
     */
    public function setTarget($target)
    {
        $this->target = $target;

        return $this;
    }

    /**
     * Get target
     *
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set color
     *
     * @param string $color
     *
     * @return Incentive
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Add entry
     *
     * @param \Hris\AdminBundle\Entity\IncentiveMatrix $entry
     *
     * @return Incentive
     */
    public function addEntry(\Hris\AdminBundle\Entity\IncentiveMatrix $entry)
    {
        $this->entries[] = $entry;

        return $this;
    }

    /**
     * Remove entry
     *
     * @param \Hris\AdminBundle\Entity\IncentiveMatrix $entry
     */
    public function removeEntry(\Hris\AdminBundle\Entity\IncentiveMatrix $entry)
    {
        $this->entries->removeElement($entry);
    }

    /**
     * Get entries
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEntries()
    {
        return $this->entries;
    }
}
