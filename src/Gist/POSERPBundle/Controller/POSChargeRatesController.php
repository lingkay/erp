<?php

namespace Gist\POSERPBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\POSERPBundle\Entity\POSChargeRates;
use Gist\ValidationException;

class POSChargeRatesController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_poserp_chg_rates';
        $this->title = 'Charge Rates';

        $this->list_title = 'Charge Rate';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new POSChargeRates();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Name', 'getRateName', 'rate_name'),
            $grid->newColumn('Rate', 'getRateValue', 'rate_name')
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();



        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setRateName($data['rate_name']);
        $o->setRateValue($data['rate_value']);
    }
}
