<?php

namespace Gist\POSERPBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\POSERPBundle\Entity\POSSettings;
use Gist\ValidationException;

class POSSettingsController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_poserp_settings';
        $this->title = 'Settings';

        $this->list_title = 'Settings';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new POSSettings();
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
            $grid->newColumn('Value', 'getValueFormatted', 'value')
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['tax'] = array(
            'incl'=>'Inclusive',
            'excl'=>'Exclusive'
        );

        $params['exchange_rules'] = array(
            'True'=>'True',
            'False'=>'False'
        );

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['name']);
        $o->setValue($data['value']);
    }
}
