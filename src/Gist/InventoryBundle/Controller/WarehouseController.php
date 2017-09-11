<?php

namespace Gist\InventoryBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Warehouse;
use Gist\InventoryBundle\Template\Controller\HasInventoryAccount;
use Gist\ValidationException;

class WarehouseController extends CrudController
{
    use HasInventoryAccount;
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_wh';
        $this->title = 'Warehouse';

        $this->list_title = 'Warehouses';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
        return new Warehouse();
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getName();
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Name', 'getName', 'name'),
            $grid->newColumn('Address', 'getAddress', 'address'),
            $grid->newColumn('Contact Number', 'getPhone', 'phone'),
            $grid->newColumn('Type', 'getType', 'type'),
        );
    }

    protected function update($o, $data, $is_new = false)
    {
        // validate name
        if (empty($data['name']))
            throw new ValidationException('Cannot leave name blank');
            
        $o->setName($data['name'])
            ->setType($data['type_id']);

        if ($data['type_id'] == 'physical') 
        {
            $o->setPhone($data['contact_num'])
            ->setAddress($data['address']);
        }
        
        if ($is_new == true)
        {
            $o->setUserCreate($this->getUser());
        }
        $this->updateHasInventoryAccount($o, $data, $is_new);
        
        if (isset($data['flag_threshold']) && $data['flag_threshold'])
            $o->setFlagThreshold();
        else
            $o->setFlagThreshold(false);

        if (isset($data['flag_shopfront']) && $data['flag_shopfront'])
            $o->setFlagShopfront();
        else
            $o->setFlagShopfront(false);

        if (isset($data['flag_stocktrack']) && $data['flag_stocktrack'])
            $o->setFlagStocktrack();
        else
            $o->setFlagStocktrack(false);

        if (isset($data['flag_finished_goods']) && $data['flag_finished_goods'])
            $o->setFlagFinishedGoods();
        else
            $o->setFlagFinishedGoods(false);

        if (isset($data['flag_equipment']) && $data['flag_equipment'])
            $o->setFlagEquipment();
        else
            $o->setFlagEquipment(false);

        if (isset($data['flag_purchased_goods']) && $data['flag_purchased_goods'])
            $o->setReceivingWarehouse();
        else
            $o->setReceivingWarehouse(false);
        
        
}

    protected function padFormParams(&$params, $o = null)
    {
        // warehouse types
        $params['wh_type_opts'] = array(
            'physical' => 'Physical',
            'virtual' => 'Virtual',
            'tank' => 'Tank',
            'adjustment' => 'Adjustment'
        );

        // stock columns
        if ($o->getID())
            $params['stock_cols'] = $this->getStockColumns();

        return $params;
    }

    protected function getStockColumns()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newColumn('Code', 'getCode', 'code', 'p'),
            $grid->newColumn('Name', 'getName', 'name', 'p'),
            $grid->newColumn('Quantity', 'getQuantity', 'quantity'),
        );
    }

    protected function setupStockGrid($id)
    {
        $grid = $this->get('gist_grid');
        $data = $this->getRequest()->query->all();
        $em = $this->getDoctrine()->getManager();

        // limit to this warehouse's stock
        $fg = $grid->newFilterGroup();
        $fg->where('w.id = ?1')
            ->setParameter(1, $id);

        // setup grid
        $gl = $grid->newLoader();
        $gl->processParams($data)
            ->setRepository('GistInventoryBundle:Stock')
            ->addJoin($grid->newJoin('p', 'product', 'getProduct'))
            ->addJoin($grid->newJoin('w', 'warehouse', 'getWarehouse'))
            ->enableCountFilter()
            ->setQBFilterGroup($fg);

        // columns
        $stock_cols = $this->getStockColumns();
        foreach ($stock_cols as $col)
            $gl->addColumn($col);

        return $gl;
    }

    public function stockGridAction($id)
    {
        $gl = $this->setupStockGrid($id);
        $gres = $gl->load();

        $resp = new Response($gres->getJSON());
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }

    public function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'name' => $o->getName(),
        );

        return $data;
    }
}
