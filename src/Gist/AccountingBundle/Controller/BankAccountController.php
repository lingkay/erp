<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\AccountingBundle\Entity\BankAccount;
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

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');

        $params['acct_type_opts'] = $am->getAccountTypeOptions();
        $params['bank_opts'] = $am->getBankOptions();
        $params['currency_opts'] = $am->getCurrencyOptions();
        $params['status_opts'] = $am->getStatusOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

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

    }
}
