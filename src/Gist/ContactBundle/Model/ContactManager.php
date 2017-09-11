<?php

namespace Gist\ContactBundle\Model;

use Gist\ContactBundle\Entity\ContactType;
use Gist\ContactBundle\Entity\PhoneType;
use Gist\ContactBundle\Entity\Address;
use Doctrine\ORM\EntityManager;

class ContactManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * 
     * @param type $id
     * @return Gist/ContactBundle/Entity/PhoneType
     */
    public function getPhoneType($id)
    {
        return $this->em->getRepository('GistContactBundle:PhoneType')->find($id);
    }
    
    /**
     * @param type $id
     * @return Gist/ContactBundle/Entity/ContactType
     */
    public function getContactType($id)
    {
        return $this->em->getRepository('GistContactBundle:ContactType')->find($id);
    }
    
    public function newAddress()
    {
        return new Address();
    }
    
    /**
     * @param type $name
     * @return Gist/ContactBundle/Entity/ContactType
     */
    public function getContactTypeName($name)
    {
        return $this->em->getRepository('GistContactBundle:ContactType')->findOneByName($name);
    }

    public function getAddress($id)
    {
        return $this->em->getRepository('GistContactBundle:Address')->find($id);
    }

    public function getPhone($id)
    {
        return $this->em->getRepository('GistContactBundle:Phone')->find($id);
    }

    public function getContactPerson($id)
    {
        return $this->em->getRepository('GistContactBundle:ContactPerson')->find($id);
    }

    public function getContactTypeOptions($filter = array())
    {
        $pgs = $this->em
            ->getRepository('GistContactBundle:ContactType')
            ->findBy(
                $filter,
                array('id' => 'ASC')
            );

        $pg_opts = array();
        foreach ($pgs as $pg)
            $pg_opts[$pg->getID()] = $pg->getName();

        return $pg_opts;
    }

    public function getPhoneTypeOptions($filter = array())
    {
        $array = array("Work"=> "Work", "Mobile"=>"Mobile","Home"=>"Home");
        return $array;
    }
}
