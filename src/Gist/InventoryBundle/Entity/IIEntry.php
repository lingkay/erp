<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\InventoryBundle\Template\Entity\HasProduct;
use Gist\CoreBundle\Template\Entity\HasQuantity;
use Gist\CoreBundle\Template\Controller\TrackCreate;
use Gist\CoreBundle\Template\Controller\TrackUpdate;


/**
 * @ORM\Entity
 * @ORM\Table(name="inv_ii_entry")
 */
class IIEntry
{    
	use HasGeneratedID;
	use HasProduct;
	use HasQuantity;

	/**
     * @ORM\ManyToOne(targetEntity="IssuedItem")
     * @ORM\JoinColumn(name="issued_id", referencedColumnName="id")
     */
    protected $issued;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $description;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $remarks;

    public function __construct()
    {
    	$this->initHasQuantity();        
        $this->initHasGeneratedID();
    }

    public function setIssued(IssuedItem $issued)
    {
    	$this->issued = $issued;
        $this->issued_id = $issued->getID();
    	return $this;
    }

    public function setRemarks($remarks)
    {
    	$this->remarks = $remarks;
    	return $this;
    }

    public function setDescription($desc)
    {
    	$this->description = $desc;
    	return $this;
    }

    public function getIssued()
    {
    	return $this->issued;
    }

    public function getRemarks()
    {
    	return $this->remarks;
    }

    public function getDescription()
    {
    	return $this->description;
    }

    public function toData()
    {
    	$data = new \stdClass();

    	$this->dataHasGeneratedID($data);
    	$this->dataHasProduct($data);
    	$this->dataHasQuantity($data);
    	$data->description = $this->description;
		$data->remarks = $this->remarks;
        $data->issued_id = $this->getIssued()->getID();

		return $data;
    }

}