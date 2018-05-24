<?php

namespace Gist\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="user_payroll_type_table")
 */
class PayrollTypeTable
{
    use HasGeneratedID;

    /** @ORM\Column(type="datetime") */
    protected $time_from;

    /** @ORM\Column(type="datetime") */
    protected $time_to;

    /** @ORM\Column(type="string", nullable=true) */
    protected $day_of_week;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;


    public function __construct()
    {

    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        return $data;
    }

    /**
     * Set timeFrom
     *
     * @param string $timeFrom
     *
     * @return PayrollTypeTable
     */
    public function setTimeFrom($timeFrom)
    {
        $this->time_from = $timeFrom;

        return $this;
    }

    /**
     * Get timeFrom
     *
     * @return string
     */
    public function getTimeFrom()
    {
        return $this->time_from->format('g:i A');
    }

    /**
     * Set timeTo
     *
     * @param string $timeTo
     *
     * @return PayrollTypeTable
     */
    public function setTimeTo($timeTo)
    {
        $this->time_to = $timeTo;

        return $this;
    }

    /**
     * Get timeTo
     *
     * @return string
     */
    public function getTimeTo()
    {
        return $this->time_to->format('g:i A');
    }

    /**
     * Set dayOfWeek
     *
     * @param string $dayOfWeek
     *
     * @return PayrollTypeTable
     */
    public function setDayOfWeek($dayOfWeek)
    {
        $this->day_of_week = $dayOfWeek;
        return $this;
    }

    /**
     * Get dayOfWeek
     *
     * @return string
     */
    public function getDayOfWeek()
    {
        return $this->day_of_week;
    }

    /**
     * Set user
     *
     * @param User $user
     *
     * @return PayrollTypeTable
     */
    public function setUser(\Gist\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \Gist\UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
}
