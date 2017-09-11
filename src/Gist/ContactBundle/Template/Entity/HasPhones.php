<?php

namespace Gist\ContactBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\ContactBundle\Entity\Phone;

trait HasPhones
{
    /** @ORM\ManyToMany(targetEntity="\Gist\ContactBundle\Entity\Phone") */
    protected $phones;

    public function initHasPhones()
    {
        $this->phones = new ArrayCollection();
    }

    public function addPhone(Phone $phone)
    {
        $this->phones->add($phone);
        return $this;
    }

    public function clearPhones()
    {
        $this->phones->clear();
        return $this;
    }

    public function removePhone(Phone $phone)
    {
        $this->phones->removeElement($phone);
        return $this;
    }

    public function getPhones()
    {
        return $this->phones;
    }
    
    public function getPrimaryPhone()
    {
        foreach ($this->phones as $p)
        {
            if ($p->getIsPrimary())
            {   
                return $p;
            }
        }
    }


    public function dataHasPhones($data)
    {
        $phones = array();
        foreach ($this->phones as $add)
        {
            $phones[] = $add->toData();
        }

        $data->phones = $phones;
    }
}
