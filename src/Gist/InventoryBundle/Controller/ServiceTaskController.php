<?php

namespace Gist\InventoryBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\ServiceTask;
use Gist\ValidationException;

class ServiceTaskController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'cat_inv_sv_task';
        $this->title = 'Service Task';

        $this->list_title = 'Service Tasks';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new ServiceTask();
    }

    protected function getObjectLabel($obj)
    {
        if ($obj == null)
            return '';
        return $obj->getName();
    }

    protected function buildData($o)
    {
        $data = array(
            'id' => $o->getID(),
            'product_id' => $o->getProduct()->getID(),
            'product_label' => $o->getProduct()->getCode() . ' - ' .  $o->getProduct()->getName(),
            'name' => $o->getName(),
            'sell_price' => $o->getSellPrice(),
            'cost_price' => $o->getCostPrice(),
        );

        return $data;
    }

    public function ajaxGetByProdAction($prod_id)
    {
        $this->checkAccess($this->route_prefix . '.view');

        $this->hookPreAction();

        $em = $this->getDoctrine()->getManager();
        $obj = $em->getRepository($this->repo)->findBy(array('product_id' => $prod_id));
        $data = array();
        foreach($obj as $index => $o)
        {
            $data[$index] = $this->buildData($o);
        }

        $resp = new Response(json_encode($data));
        $resp->headers->set('Content-Type', 'application/json');

        return $resp;
    }
}