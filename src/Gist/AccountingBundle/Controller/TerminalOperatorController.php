<?php

namespace Gist\AccountingBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\AccountingBundle\Entity\TerminalOperator;
use Gist\ValidationException;

class TerminalOperatorController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_terminals_terminal_operator';
        $this->title = 'Terminal Operator';

        $this->list_title = 'Terminal Operators';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new TerminalOperator();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Company', 'getName', 'name'),
            $grid->newColumn('Status', 'getStatus', 'status')
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        //GIST Accounting Service
        $am = $this->get('gist_accounting');
        $params['status_opts'] = $am->getStatusOptions();

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setStatus($data['status']);

    }
}
