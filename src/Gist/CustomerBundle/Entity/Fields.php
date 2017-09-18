<?php

namespace Gist\CustomerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;

use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="customer_customer_fields")
 */

class Fields
{


    use HasGeneratedID;
    // use TrackCreate;



    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $field_name;

    /** @ORM\Column(type="string", length=245, nullable=false) */
    protected $field_display_name;

    /** @ORM\Column(type="boolean", length=245, nullable=true) */
    protected $required_flag;

    /** @ORM\Column(type="boolean", length=245, nullable=false) */
    protected $visibility_flag;

    public function __construct()
    {
        //$this->initTrackCreate();
    }

    /**
     * Set fieldName
     *
     * @param string $fieldName
     *
     * @return Fields
     */
    public function setFieldName($fieldName)
    {
        $this->field_name = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->field_name;
    }

    /**
     * Set requiredFlag
     *
     * @param string $requiredFlag
     *
     * @return Fields
     */
    public function setRequiredFlag($requiredFlag)
    {
        $this->required_flag = $requiredFlag;

        return $this;
    }

    /**
     * Get requiredFlag
     *
     * @return string
     */
    public function getRequiredFlag()
    {
        return $this->required_flag;
    }


    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        // $this->dataTrackCreate($data);
        return $data;
    }

    /**
     * Set visibilityFlag
     *
     * @param string $visibilityFlag
     *
     * @return Fields
     */
    public function setVisibilityFlag($visibilityFlag)
    {
        $this->visibility_flag = $visibilityFlag;

        return $this;
    }

    /**
     * Get visibilityFlag
     *
     * @return string
     */
    public function getVisibilityFlag()
    {
        return $this->visibility_flag;
    }

    /**
     * Set fieldDisplayName
     *
     * @param string $fieldDisplayName
     *
     * @return Fields
     */
    public function setFieldDisplayName($fieldDisplayName)
    {
        $this->field_display_name = $fieldDisplayName;

        return $this;
    }

    /**
     * Get fieldDisplayName
     *
     * @return string
     */
    public function getFieldDisplayName()
    {
        return $this->field_display_name;
    }
}
