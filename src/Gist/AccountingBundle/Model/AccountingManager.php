<?php

namespace Gist\AccountingBundle\Model;

use Gist\UserBundle\Entity\User;
use Gist\UserBundle\Entity\Group;
use Gist\UserBundle\Entity\ItemsList;
use Doctrine\ORM\EntityManager;

class AccountingManager
{
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function getAccountTypeOptions()
    {
        return array(
            'Savings' => 'Savings',
            'Current' => 'Current',
            'Credit Card' => 'Credit Card',
            'Debit Card' => 'Debit Card'
        );
    }

    public function getCurrencyOptions()
    {
        return array(
            'PHP' => 'PHP - Philippine Peso',
            'USD' => 'USD - United States Dollar',
            'HKD' => 'HKD - Hong Kong Dollar',
            'EUR' => 'EUR - European Euro'
        );
    }

    public function getStatusOptions()
    {
        return array(
            'Active' => 'Active',
            'Inactive' => 'Inactive',
            'Deleted' => 'Deleted'
        );
    }

    

    // public function getItemOptions($filter = array())
    // {
    //     return $this->getOptionsArray(
    //         'GistUserBundle:ItemsList',
    //         $filter,
    //         array('name' => 'ASC'),
    //         'getID',
    //         'getFormattedName'
    //     );
    // }

    // public function findUser($id)
    // {
    //     return $this->em->getRepository('GistUserBundle:User')
    //         ->find($id);
    // }

    // public function findGroup($id)
    // {
    //     return $this->em->getRepository('GistUserBundle:Group')
    //         ->find($id);
    // }


}
