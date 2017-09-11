<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_appraisal_settings")
 */
class AppraisalSettings
{
    use HasGeneratedID;
    use TrackCreate;
    use HasName;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $obj_count;

    /** @ORM\Column(type="float", nullable=true) */
    protected $obj_percentage;    

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);

        return $data;
    }

    /**
     * Set objCount
     *
     * @param integer $objCount
     *
     * @return AppraisalSettings
     */
    public function setObjCount($objCount)
    {
        $this->obj_count = $objCount;

        return $this;
    }

    /**
     * Get objCount
     *
     * @return integer
     */
    public function getObjCount()
    {
        return $this->obj_count;
    }

    /**
     * Set objPercentage
     *
     * @param integer $objPercentage
     *
     * @return AppraisalSettings
     */
    public function setObjPercentage($objPercentage)
    {
        $this->obj_percentage = $objPercentage;

        return $this;
    }

    /**
     * Get objPercentage
     *
     * @return integer
     */
    public function getObjPercentage()
    {
        return $this->obj_percentage;
    }
}
