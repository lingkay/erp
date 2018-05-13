<?php

namespace Gist\POSERPBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\POSERPBundle\Entity\POSTransaction;
use Gist\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class POSTransactionController extends CrudController
{
    public function __construct()
    {
        $this->route_prefix = 'gist_poserp_transaction';
        $this->title = 'Transaction';

        $this->list_title = 'Transactions';
        $this->list_type = 'dynamic';
    }

    protected function newBaseClass()
    {
        return new POSTransaction();
    }

    public function callbackGrid($id)
    {
        $params = array(
            'id' => $id,
            'route_edit' => $this->getRouteGen()->getEdit(),
            'route_delete' => $this->getRouteGen()->getDelete(),
            'prefix' => $this->route_prefix,
        );

        $this->padGridParams($params, $id);

        $engine = $this->get('templating');
        return $engine->render(
            'GistPOSERPBundle:POSTransaction:action.html.twig',
            $params
        );
    }

    protected function getObjectLabel($obj)
    {
        return $obj->getID();
    }

    protected function getGridJoins()
    {
        $grid = $this->get('gist_grid');
        return array(
            $grid->newJoin('l', 'pos_location', 'getPOSLocation'),
            $grid->newJoin('c', 'customer', 'getCustomer'),
        );
    }

    protected function getGridColumns()
    {
        $grid = $this->get('gist_grid');

        return array(
            $grid->newColumn('Receipt Number', 'getTransDisplayId', 'trans_display_id'),
            $grid->newColumn('Transaction Date','getDateCreateFormatted','date_create'),
            $grid->newColumn('Type', 'getTransactionModeFormatted', 'mode'),
            $grid->newColumn('Amount', 'getCartOrigTotal', 'cart_orig_total'),
            $grid->newColumn('Location', 'getName', 'name','l'),
            $grid->newColumn('Customer', 'getNameFormatted', 'last_name','c'),
        );
    }

    protected function padFormParams(&$params, $o = null)
    {
        $em = $this->getDoctrine()->getManager();

        $params['customer'] = $em->getRepository('GistCustomerBundle:Customer')->findOneBy(array('id'=>$o->getCustomerId()));

        return $params;
    }

    protected function update($o, $data, $is_new = false)
    {
        $o->setRateName($data['rate_name']);
        $o->setRateValue($data['rate_value']);
    }

    public function getChargeRatesAction()
    {   
        header("Access-Control-Allow-Origin: *");
        $em = $this->getDoctrine()->getManager();
        $rates = $em->getRepository('GistPOSERPBundle:POSChargeRates')->findAll();
        $list_opts = [];
        foreach ($rates as $c) {
            $list_opts[] = array('id'=>$c->getID(), 'name'=> $c->getRateName(), 'value'=> $c->getRateValue());

        }
        return new JsonResponse($list_opts);
    }
}
