<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\AccountingBundle\Entity\BankAccount;
use Gist\AccountingBundle\Entity\BankCharge;
use Gist\ValidationException;

class BankAccountController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_bank_accounts';
        $this->title = 'Bank Account';

        $this->list_title = 'Bank Accounts';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new BankAccount();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('b', 'bank', 'getBank'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Bank', 'getName', 'name', 'b'),
            $grid->newColumn('Account Number', 'getAccountNumber', 'account_number'),
            $grid->newColumn('Account Name', 'getName', 'name'),
            $grid->newColumn('Branch', 'getBranch', 'branch'),
            $grid->newColumn('Type', 'getType', 'type'),
            $grid->newColumn('Status', 'getStatus', 'status')
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');

        $params['acct_type_opts'] = $am->getAccountTypeOptions();
        $params['bank_opts'] = $am->getBankOptions();
        $params['currency_opts'] = $am->getCurrencyOptions();
        $params['status_opts'] = $am->getStatusOptions();

        $params['rates'] = $em->getRepository('GistAccountingBundle:BankCharge')->findBy(array('bank'=>$o->getID()));

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        // echo "<pre>";
        // var_dump($data);
        // echo "</pre>";
        // die();
        $em = $this->getDoctrine()->getManager();
        //remove all rates first
        $ext_rates = $em->getRepository('GistAccountingBundle:BankCharge')->findBy(array('bank'=>$o->getID()));
        foreach ($ext_rates as $i => $rate) {
            $em->remove($rate);
        }
        $em->flush();

        $o->setName($data['name']);
        $o->setAccountNumber($data['account_number']);
        $o->setBranch($data['branch']);
        $o->setCurrency($data['currency']);
        $o->setType($data['type']);
        $o->setChartOfAccount($data['chart_of_account']);
        $o->setStatus($data['status']);

        if (isset($data['bank'])) {
            $bank = $em->getRepository('GistAccountingBundle:Bank')->find($data['bank']);
            $o->setBank($bank);
        }


        if (isset($data['rate_name'])) {
            foreach ($data['rate_name'] as $i => $rate_name) {
                $rate = new BankCharge();
                $rate->setRateName($rate_name);
                $rate->setRateValue($data['rate_value'][$i]);
                $rate->setBank($o);
                $em->persist($rate);
            }
        }

    }
}
