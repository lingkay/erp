<?php

namespace Gist\UserBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\UserBundle\Entity\ItemsList;
use Gist\ValidationException;

class ItemsListController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_user_items_list';
        $this->title = 'Item';

        $this->list_title = 'Items';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new ItemsList();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Item Name', 'getName', 'name'),
            $grid->newColumn('Serial Number', 'getSerialNumber', 'serial_number'),
        );
    }

    protected function padFormParams(&$params, $user = null)
    {
        $em = $this->getDoctrine()->getManager();


        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setName($data['item_name']);
        $o->setSerialNumber($data['serial_number']);
    }


}
