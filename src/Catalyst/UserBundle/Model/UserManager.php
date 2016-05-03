<?php

namespace Catalyst\UserBundle\Model;

use Catalyst\UserBundle\Entity\User;
use Catalyst\UserBundle\Entity\Group;
use Doctrine\ORM\EntityManager;

class UserManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function newUser()
    {
        return new User();
    }

    public function newGroup()
    {
        return new Group();
    }

    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $objects = $this->em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    public function getUserOptions($filter = array())
    {
        return $this->getOptionsArray(
            'CatalystUserBundle:User',
            $filter, 
            array('username' => 'ASC'),
            'getID',
            'getUsername'
        );
    }
    
    public function getUsersNamesOptions($filter = array())
    {
        return $this->getOptionsArray(
            'CatalystUserBundle:User',
            $filter, 
            array('username' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getGroupOptions($filter = array())
    {
        return $this->getOptionsArray(
            'CatalystUserBundle:Group',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function findUser($id)
    {
        return $this->em->getRepository('CatalystUserBundle:User')
            ->find($id);
    }

    public function findGroup($id)
    {
        return $this->em->getRepository('CatalystUserBundle:Group')
            ->find($id);
    }


}
