<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="pay_tax_matrix")
 */
class TaxMatrix
{
    use HasGeneratedID;
    use HasName;

    /**
     * @ORM\OneToMany(targetEntity="TaxMatrixTable", mappedBy="tax", cascade={"persist"})
     */
    protected $entries;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $is_amt_percent;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $is_retroactive;

    public function __construct()
    {
        $this->initHasName();
    }

    public function setIsAmountPercent($val)
    {
        $this->is_amt_percent = $val;
        return $this;
    }

    public function getIsAmountPercent()
    {
        return $this->is_amt_percent;
    }

    public function setIsRetroActive($val)
    {
        $this->is_retroactive = $val;
        return $this;
    }

    public function getIsRetroActive()
    {
        return $this->is_retroactive;
    }

    public function getEntries()
    {
        return $this->entries;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);
        return $data;
    }
}
