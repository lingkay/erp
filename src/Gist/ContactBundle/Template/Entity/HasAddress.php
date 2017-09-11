<?php

namespace Gist\ContactBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\ContactBundle\Entity\Address;

trait HasAddress
{
    /** 
     * @ORM\ManyToOne(targetEntity="\Gist\ContactBundle\Entity\Address") 
     * @ORM\JoinColumn(name="address_id", referencedColumnName="id")
     */
    protected $address; 

    protected function initHasAddress()
    {
        $this->address = null;
    }

    public function setAddress(Address $address)
    {
        $this->address = $address;
        return $this;
    }

    public function getAddress()
    {
        return $this->address;
    }

    public function dataHasAddress($data)
    {
        if ($this->address == null)
            $data->address = null;
        else
            $data->address = $this->address->toData();
    }
}
