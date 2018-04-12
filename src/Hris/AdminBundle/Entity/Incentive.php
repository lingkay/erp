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
}
