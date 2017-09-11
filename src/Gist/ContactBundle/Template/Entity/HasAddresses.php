<?php

namespace Gist\ContactBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\ContactBundle\Entity\Address;

trait HasAddresses
{
    /** @ORM\ManyToMany(targetEntity="\Gist\ContactBundle\Entity\Address") */
    protected $addresses;

    protected function initHasAddresses()
    {
        $this->addresses = new ArrayCollection();
    }

    public function addAddress(Address $address)
    {
        $this->addresses->add($address);
        return $this;
    }

    public function clearAddresses()
    {
        $this->addresses->clear();
        return $this;
    }

    public function removeAddress(Address $address)
    {
        $this->addresses->removeElement($address);
        return $this;
    }

    public function getAddresses()
    {
        return $this->addresses;
    }
    
    public function findAddress($id)
    {
        foreach($this->addresses as $address){
            if($address->getID() === $id){
                return $address;
            }
        }
    }
    public function getPrimaryAddress()
    {
        foreach ($this->addresses as $add)
        {
            if ($add->getIsPrimary())
            {   
                return $add;
            }
        }
    }

    public function dataHasAddresses($data)
    {
        $addresses = array();
        foreach ($this->addresses as $add)
        {
            $addresses[] = $add->toData();
        }

        $data->addresses = $addresses;
    }
}
