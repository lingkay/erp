<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;
//use Gist\ContactBundle\Template\Entity\HasAddress;
//use Gist\ContactBundle\Template\Entity\HasPhones;
use Gist\InventoryBundle\Template\Entity\HasInventoryAccount;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_warehouse")
 */
class Warehouse
{
    use HasGeneratedID;
    use HasName;
    use TrackCreate;
    //use HasAddress;
    use HasInventoryAccount;
    //use HasPhones;

    /** @ORM\Column(type="string", length=25, nullable=true) */
    protected $internal_code;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_threshold;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_shopfront;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_finished_goods;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_equipment;

    /** @ORM\Column(type="boolean", nullable=true) */
    protected $flag_purchased_goods;

    /** @ORM\Column(type="integer", nullable=true) */
    protected $pm_terms;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $type;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $address;

    /** @ORM\Column(type="string", length=250, nullable=true) */
    protected $phone;

    public function __construct()
    {
        //$this->initHasAddress();
        $this->initHasInventoryAccount();
        //$this->initHasPhones();
        $this->initTrackCreate();

        $this->flag_threshold = true;
        $this->flag_shopfront = false;
        $this->internal_code = '';
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setAddress($address)
    {
        $this->address = $address;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function setInternalCode($code)
    {
        $this->internal_code = $code;
        return $this;
    }

    public function setFlagThreshold($flag = true)
    {
        $this->flag_threshold = $flag;
        return $this;
    }

    public function setFlagStocktrack($flag = true)
    {
        $this->flag_stocktrack = $flag;
        return $this;
    }

    public function setFlagShopfront($flag = true)
    {
        $this->flag_shopfront = $flag;
        return $this;
    }

    public function setFlagFinishedGoods($flag = true)
    {
        $this->flag_finished_goods = $flag;
        return $this;
    }

    public function setFlagEquipment($flag = true)
    {
        $this->flag_equipment = $flag;
        return $this;
    }

    public function setReceivingWarehouse($flag = true)
    {
        $this->flag_purchased_goods = $flag;
        return $this;
    }

    public function setPaymentTerm($pm)
    {
        $this->pm_terms = $pm;
        return $this;
    }

    public function getInternalCode()
    {
        return $this->internal_code;
    }

    public function getPaymentTerm()
    {
        return $this->pm_terms;
    }

    public function canTrackThreshold()
    {
        return $this->flag_threshold;
    }

    public function isShopfront()
    {
        return $this->flag_shopfront;
    }

    public function isFinishedGoods()
    {
        return $this->flag_finished_goods;
    }

    public function isEquipmentWarehouse()
    {
        return $this->flag_equipment;
    }

    public function isReceivingWarehouse()
    {
        return $this->flag_purchased_goods;
    }

    public function isStocktrack()
    {
        return $this->flag_stocktrack;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function toData()
    {
        $data = new \stdClass(); 

        $this->dataHasGeneratedID($data);
       // $this->dataHasAddress($data);
        $this->dataTrackCreate($data);
        //$this->dataHasPhones($data);

        $data->internal_code = $this->internal_code;
        $data->flag_threshold = $this->flag_threshold;
        $data->flag_shopfront = $this->flag_shopfront;
        $data->pm_terms = $this->pm_terms;

        return $data;
    }
}

