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
        return $obj->getName();
    }

    // protected function getGridJoins()
    // {
    //     $grid = $this->get('gist_grid');
    //     return array(
    //         $grid->newJoin('a', 'area', 'getArea'),
    //     );
    // }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Actual Location', 'getActualLocation', 'actual_location'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');

        $params['acct_type_opts'] = $am->getAccountTypeOptions();
        $params['terminal_company_opts'] = $am->getTerminalCompanyOptions();
        $params['currency_opts'] = $am->getCurrencyOptions();
        $params['status_opts'] = $am->getStatusOptions();
        $params['bank_opts'] = $am->getBankOptions();
        $params['bank_account_opts'] = $am->getBankAccountOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setAccountNumber($data['account_number']);
        $o->setBranch($data['branch']);
        $o->setCurrency($data['currency']);
        $o->setType($data['type']);
        $o->setChartOfAccount($data['chart_of_account']);
        $o->setStatus($data['status']);

    }

    // protected function getOptionsArray($repo, $filter, $order, $id_method, $value_method)
    // {
    //     $em = $this->getDoctrine()->getManager();
    //     $objects = $em->getRepository($repo)
    //         ->findBy(
    //             $filter,
    //             $order
    //         );

    //     $opts = array();
    //     foreach ($objects as $o)
    //         $opts[$o->$id_method()] = $o->$value_method();

    //     return $opts;
    // }

    // public function getAreaOptions($filter = array())
    // {
    //     return $this->getOptionsArray(
    //         'GistLocationBundle:Areas',
    //         $filter, 
    //         array('name' => 'ASC'),
    //         'getID',
    //         'getName'
    //     );
    // }


}
