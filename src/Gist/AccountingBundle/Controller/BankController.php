<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\AccountingBundle\Entity\Bank;
use Gist\ValidationException;

class BankController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_accounting_banks';
        $this->title = 'Bank';

        $this->list_title = 'Banks';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new Bank();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Status', 'getStatus', 'status')
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');
        $params['currency_opts'] = $am->getCurrencyOptions();
        $params['status_opts'] = $am->getStatusOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setStatus($data['status']);

    }
}
