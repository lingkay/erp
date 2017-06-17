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

    public function getPaymentTypeOptions()
    {
        return array(
            'Quota' => 'Quota',
            'Rental' => 'Rental'
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

    public function getTerminalCompanyOptions()
    {
        return array(
            'GHL' => 'GHL',
            'Global Pay' => 'Global Pay',
            'BDO' => 'BDO',
            'Tangent' => 'Tangent'
        );
    }

    // public function getBankOptions()
    // {
    //     return array(
    //         'BDO' => 'BDO',
    //         'BPI' => 'BPI',
    //         'Citibank' => 'Citibank',
    //         'Security Bank' => 'Security Bank',
    //         'HSBC' => 'HSBC',
    //         'Metrobank' => 'Metrobank',
    //         'Unionbank' => 'Unionbank',
    //         'RCBC' => 'RCBC',
    //         'Chinabank' => 'Chinabank'
    //     );
    // }


    protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    {
        $em = $this->em;
        $objects = $em->getRepository($repo)
            ->findBy(
                $filter,
                $order
            );

        $opts = array();
        foreach ($objects as $o)
            $opts[$o->$id_method()] = $o->$value_method();

        return $opts;
    }

    

    public function getBankAccountOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:BankAccount',
            $filter,
            array('name' => 'ASC'),
            'getID',
            'getNameFormatted'
        );
    }

    public function getBankOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:Bank',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getPOSLocationOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistLocationBundle:POSLocations',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

    public function getTerminalOperatorOptions($filter = array())
    {
        return $this->getOptionsArray(
            'GistAccountingBundle:TerminalOperator',
            $filter,
            array('id' => 'ASC'),
            'getID',
            'getName'
        );
    }

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
