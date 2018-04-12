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

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $fixed;

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_authorized_giver", referencedColumnName="id")
     */
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
}
