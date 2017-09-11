<?php

namespace Gist\ContactBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\ContactBundle\Entity\ContactPerson;

trait HasContactPersons
{
    /** @ORM\ManyToMany(targetEntity="\Gist\ContactBundle\Entity\ContactPerson") */
    protected $contact_persons;

    public function initHasContactPersons()
    {
        $this->contact_persons = new ArrayCollection();
    }

    public function addContactPerson(ContactPerson $contact_person)
    {
        $this->contact_persons->add($contact_person);
        return $this;
    }

    public function clearContactPersons()
    {
        $this->contact_persons->clear();
        return $this;
    }

    public function removeContactPersons(ContactPerson $contact_persons)
    {
        $this->contact_persons->removeElement($contact_persons);
        return $this;
    }

    public function getContactPersons()
    {
        return $this->contact_persons;
    }
    

    public function dataHasContactPersons($data)
    {
        $contact_persons = array();
        foreach ($this->contact_persons as $add)
        {
            $contact_persons[] = $add->toData();
        }

        $data->contact_persons = $contact_persons;
    }
}
