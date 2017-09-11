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

    /** @ORM\Column(type="string", length=245, nullable=true) */
    protected $required_flag;




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
}
