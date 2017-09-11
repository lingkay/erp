<?php

namespace Gist\UserBundle\Model;

use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use Gist\UserBundle\Entity\ItemsList;
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
        $opts[-1] = 'None';
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    public function getUserOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:User',
            $filter, 
            array('username' => 'ASC'),
            'getID',
            'getUsername'
        );
    }
    
    public function getUsersNamesOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:User',
            $filter, 
            array('username' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getGroupOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:Group',
            $filter, 
            array('name' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getDepartmentOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:Department',
            $filter,
            array('department_name' => 'ASC'),
            'getID',
            'getDepartmentName'
        );
    }

    public function getItemOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistUserBundle:ItemsList',
            $filter,
            array('name' => 'ASC'),
            'getID',
            'getFormattedName'
        );
    }

    public function findUser($id)
    {
        return $this->em->getRepository('GistUserBundle:User')
            ->find($id);
    }

    public function findGroup($id)
    {
        return $this->em->getRepository('GistUserBundle:Group')
            ->find($id);
    }


}
