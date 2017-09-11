<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\AccountingBundle\Entity\Terminal;
use Gist\ValidationException;

class TerminalController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_terminals_terminal';
        $this->title = 'Terminal';

        $this->list_title = 'Terminals';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Terminal();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getMID();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('a', 'bank_account', 'getBankAccount'),
            $grid->newJoin('l', 'actual_location', 'getActualLocation'),
            $grid->newJoin('x', 'registered_location', 'getRegisteredLocation'),
            $grid->newJoin('t', 'terminal_operator', 'getTerminalOperator'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Actual Location', 'getName', 'name','l'),
            $grid->newColumn('Registered Location', 'getName', 'name','x'),
            $grid->newColumn('Operator', 'getName', 'name','t'),
            $grid->newColumn('Bank', 'getBank', 'bank'),
            $grid->newColumn('Account Number', 'getNameFormatted', 'id','a'),
            $grid->newColumn('MID', 'getMID', 'mid'),
            $grid->newColumn('TID', 'getTID', 'tid'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');

        $params['acct_type_opts'] = $am->getAccountTypeOptions();
        $params['terminal_company_opts'] = $am->getTerminalCompanyOptions();
        $params['terminal_operator_opts'] = $am->getTerminalOperatorOptions();
        $params['currency_opts'] = $am->getCurrencyOptions();
        $params['status_opts'] = $am->getStatusOptions();
        $params['payment_type_opts'] = $am->getPaymentTypeOptions();
        $params['bank_opts'] = $am->getBankOptions();
        $params['bank_account_opts'] = $am->getBankAccountOptions();
        $params['pos_locations_opts'] = $am->getPOSLocationOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $em = $this->getDoctrine()->getManager();

        if (isset($data['actual_location'])) {
            $actual_location = $em->getRepository('GistLocationBundle:POSLocations')->find($data['actual_location']);
            $o->setActualLocation($actual_location);
        }

        if (isset($data['registered_location'])) {
            $registered_location = $em->getRepository('GistLocationBundle:POSLocations')->find($data['registered_location']);
            $o->setRegisteredLocation($registered_location);
        }


        // $o->setActualLocation($data['actual_location']);
        // $o->setRegisteredLocation($data['registered_location']);
        if (isset($data['bank'])) {
           $o->setBank(implode(",", $data['bank']));
        }
        
        $o->setMID($data['mid']);
        $o->setTID($data['tid']);
        $o->setBrand($data['brand']);
        $o->setModel($data['model']);
        $o->setSerialNumber($data['serial_number']);
        $o->setSimCardNumber($data['sim_number']);
        $o->setRemarks($data['remarks']);
        $o->setPaymentType($data['payment_type']);
        $o->setStatus($data['status']);
        $o->setTerminalOf($data['terminal_of']);
        if (isset($data['bank_account'])) {
            $bank_account = $em->getRepository('GistAccountingBundle:BankAccount')->find($data['bank_account']);
            $o->setBankAccount($bank_account);
        }

        if (isset($data['terminal_operator'])) {
            $terminal_operator = $em->getRepository('GistAccountingBundle:TerminalOperator')->find($data['terminal_operator']);
            $o->setTerminalOperator($terminal_operator);
        }
    }
}
