<?php

namespace Hris\WorkforceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\MediaBundle\Template\Entity\HasUpload;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="hr_issued_property")
 */
class IssuedProperty
{
    const TYPE_CAR = 'Car';
    const TYPE_LAPTOP = 'Laptop';
    const TYPE_PHONE = 'Phone';
    use HasGeneratedID;
    use TrackCreate;
    use HasUpload;

    /**
     * @ORM\ManyToOne(targetEntity="Hris\WorkforceBundle\Entity\Employee")
     * @ORM\JoinColumn(name="employee_id", referencedColumnName="id")
     */
    protected $employee;

    /** @ORM\Column(type="string", length=10) */
    protected $item_type;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $item_name;

    /** @ORM\Column(type="text", length=50, nullable=true) */
    protected $item_code;

    /** @ORM\Column(type="text", nullable=true) */
    protected $item_desc;

    /** @ORM\Column(type="text", nullable=true) */
    protected $addtl_details;

    /** @ORM\Column(type="datetime") */
    protected $date_issued;

    /** @ORM\Column(type="datetime", nullable=true) */
    protected $date_returned;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    /**
     * Set itemName
     *
     * @param string $itemName
     *
     * @return IssuedProperty
     */
    public function setItemName($itemName)
    {
        $this->item_name = $itemName;

        return $this;
    }

    /**
     * Get itemName
     *
     * @return string
     */
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * Set itemCode
     *
     * @param string $itemCode
     *
     * @return IssuedProperty
     */
    public function setItemCode($itemCode)
    {
        $this->item_code = $itemCode;

        return $this;
    }

    /**
     * Get itemCode
     *
     * @return string
     */
    public function getItemCode()
    {
        return $this->item_code;
    }

    /**
     * Set itemDesc
     *
     * @param string $itemDesc
     *
     * @return IssuedProperty
     */
    public function setItemDesc($itemDesc)
    {
        $this->item_desc = $itemDesc;

        return $this;
    }

    /**
     * Get itemDesc
     *
     * @return string
     */
    public function getItemDesc()
    {
        return $this->item_desc;
    }

    /**
     * Set dateIssued
     *
     * @param \DateTime $dateIssued
     *
     * @return IssuedProperty
     */
    public function setDateIssued($dateIssued)
    {
        $this->date_issued = $dateIssued;

        return $this;
    }

    /**
     * Get dateIssued
     *
     * @return \DateTime
     */
    public function getDateIssued()
    {
        if ($this->date_issued == null) {
            return $this->date_issued;
        } else {
            return $this->date_issued->format('F j, Y');
        }
        return $this->date_issued;
    }

    public function getDateIssuedFormatted()
    {
        if ($this->date_issued != null) {
            return $this->date_issued->format('m/d/Y');
        }
        return $this->date_issued;
    }

    public function getDateReturnedFormatted()
    {
        if ($this->date_returned != null) {
            return $this->date_returned->format('m/d/Y');
        }
        return $this->date_returned;
    }

    /**
     * Set dateReturned
     *
     * @param \DateTime $dateReturned
     *
     * @return IssuedProperty
     */
    public function setDateReturned($dateReturned)
    {
        $this->date_returned = $dateReturned;

        return $this;
    }

    /**
     * Get dateReturned
     *
     * @return \DateTime
     */
    public function getDateReturned()
    {
        if ($this->date_returned == null) {
            return null;
        } else {
            return $this->date_returned->format('F j, Y');
        }
        return $this->date_returned;
    }

    /**
     * Set employee
     *
     * @param \Hris\WorkforceBundle\Entity\Employee $employee
     *
     * @return IssuedProperty
     */
    public function setEmployee(\Hris\WorkforceBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \Hris\WorkforceBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function getEmployeeName()
    {
        return $this->employee->getDisplayName();
    }

    /**
     * Set addtlDetails
     *
     * @param string $addtlDetails
     *
     * @return IssuedProperty
     */
    public function setAddtlDetails($addtlDetails)
    {
        $this->addtl_details = $addtlDetails;

        return $this;
    }

    /**
     * Get addtlDetails
     *
     * @return string
     */
    public function getAddtlDetails()
    {
        return $this->addtl_details;
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        
        return $data;
    }

    /**
     * Set itemType
     *
     * @param string $itemType
     *
     * @return IssuedProperty
     */
    public function setItemType($itemType)
    {
        $this->item_type = $itemType;

        return $this;
    }

    /**
     * Get itemType
     *
     * @return string
     */
    public function getItemType()
    {
        return $this->item_type;
    }
}
