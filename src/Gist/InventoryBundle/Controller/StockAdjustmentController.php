<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\CrudController;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\StockHistory;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Gist\ValidationException;
use Gist\InventoryBundle\Model\InventoryException;


class StockAdjustmentController extends CrudController
{
	public function __construct()
    {
        $this->route_prefix = 'cat_inv_adjust';
        $this->title = 'Stock Adjustment';

        $this->list_title = 'Stock Adjustment';
        $this->list_type = 'static';
    }

    protected function newBaseClass()
    {
    }

    protected function getObjectLabel($obj)
    {
    }

    // TODO: this should refer to settings but we don't have a way to create
    //       inventory accounts on their own yet
    protected function getAdjustmentAccount()
    {
        $em = $this->getDoctrine()->getManager();

        $config = $this->get('gist_configuration');
        $adj_warehouse_id = $config->get('gist_warehouse_stock_adjustment');
        $adj_warehouse = $em->getRepository('GistInventoryBundle:Warehouse')->find($adj_warehouse_id);

        $acc = $em->getRepository('GistInventoryBundle:Account')->find($adj_warehouse->getInventoryAccount()->getID());

        return $acc;
    }

    public function indexAction($wh_id = null)
    {
        $this->hookPreAction();

        $gl = $this->setupGridLoader();

        $params = $this->getViewParams('List');
        $params['title_icon'] = 'fa-suitcase';
        $twig_file = 'GistInventoryBundle:StockAdjustment:index.html.twig';

        $inv = $this->get('gist_inventory');
        $params['wh_id'] = $wh_id;
        $params['list_title'] = $this->list_title;
        $params['grid_cols'] = $gl->getColumns();
        $params['wh_opts'] = array('0'=>'Select Warehouse') + $inv->getWarehouseOptionsByType('physical','id') + $inv->getWarehouseOptionsByType('virtual','id');
        $params['prodgroup_opts'] = $inv->getProductGroupOptions();  

        return $this->render($twig_file, $params);
    }

    protected function generateAdjustmentEntries($data)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');

        // initialize entries
        $entries = array();

        // warehouse
        $wh_id = $data['from_wh_id'];
        $wh = $em->getRepository('GistInventoryBundle:Warehouse')->find($wh_id);
        if ($wh == null)
            throw new ValidationException('Could not find warehouse.');
        $wh_acc = $wh->getInventoryAccount();

        // adjustment account
        $adj_acc = $this->getAdjustmentAccount();

        // process each row
        foreach ($data['prod_id'] as $index => $prod_id)
        {
            if ($data['qty'][$index] != '') 
            {
                // product
                $prod = $em->getRepository('GistInventoryBundle:Product')->find($prod_id);
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                // old quantity
                $new_qty = $data['qty'][$index];
                $old_qty = $inv->getStockCount($wh_acc, $prod);

                // same amount, no change
                if ($new_qty == $old_qty)
                    continue;

                // entry for warehouse
                $wh_entry = new Entry();
                $wh_entry->setInventoryAccount($wh_acc)
                    ->setProduct($prod);

                // entry for adjustment
                $adj_entry = new Entry();
                $adj_entry->setInventoryAccount($adj_acc)
                    ->setProduct($prod);

                // check if debit or credit
                if ($new_qty > $old_qty)
                {
                    $qty = $new_qty - $old_qty;
                    $wh_entry->setDebit($qty);
                    $adj_entry->setCredit($qty);
                }
                else
                {
                    $qty = $old_qty - $new_qty;
                    $wh_entry->setCredit($qty);
                    $adj_entry->setDebit($qty);
                }

                $entries[] = $wh_entry;
                $entries[] = $adj_entry;
            }
        }

        return $entries;
    }

    public function addSubmitAction($wh_id = null)
    {


        $inv = $this->get('gist_inventory');
        $log = $this->get('gist_log');

        $em = $this->getDoctrine()->getManager();
        
        //die($url);

        $data = $this->getRequest()->request->all();
        $url = $this->generateUrl('cat_inv_adjust_index', array('wh_id'=>$data['from_wh_id']));

        // var_dump($data);
        // die();

        $config = $this->get('gist_configuration');
        $adj_warehouse_id = $config->get('gist_warehouse_stock_adjustment');

        if ($data['from_wh_id'] == $adj_warehouse_id)
        {
            $this->addFlash('error', 'Cannot add to Adjustment Warehouse.');
            return $this->redirect($url);
        }

        try
        {
            // process entries
            $entries = $this->generateAdjustmentEntries($data);

            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($this->getUser())
                ->setDescription($data['desc']);

            // add entries
            foreach ($entries as $ent)
                $trans->addEntry($ent);

            $inv->persistTransaction($trans);
            $em->flush();

            $wh_id = $data['from_wh_id'];
            $wh = $em->getRepository('GistInventoryBundle:Warehouse')->find($wh_id);
            if ($wh == null)
                throw new ValidationException('Could not find warehouse.');
            $wh_acc = $wh->getInventoryAccount();

            foreach ($data['prod_id'] as $index => $prod_id)
            {

                // product
                $prod = $em->getRepository('GistInventoryBundle:Product')->find($prod_id);
                if ($prod == null)
                    throw new ValidationException('Could not find product.');

                // warehouse
                $wh = $em->getRepository('GistInventoryBundle:Warehouse')->find($wh_id);
                if ($wh == null)
                    throw new ValidationException('Could not find warehouse.');

                $stocks = $em->getRepository('GistInventoryBundle:Stock');
                $quantity = $stocks->findOneBy(array('inv_account' => $wh->getInventoryAccount(), 'product' => $prod_id))->getQuantity();

                $stockhistory = new StockHistory();
                $stockhistory->setTransaction($trans)
                            ->setProduct($prod)
                            ->setInventoryAccount($wh->getInventoryAccount())
                            ->setDateCreate(new \DateTime('now'))
                            ->setQuantity($quantity);

                $em->persist($stockhistory);
            }

            // log
            $odata = $trans->toData();
            $log->log('cat_inv_trans_add', 'added Inventory Transaction ' . $trans->getID() . '.', $odata);
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());

            return $this->redirect($url);
        }
        catch (InventoryException $e)
        {
            $this->addFlash('error', $e->getMessage());

            return $this->redirect($url);
        }

        $this->addFlash('success', 'Stock adjustment transaction successful.');
        return $this->redirect($url);
    }

    public function getWarehouseProductsAction($wh_id)
    {
        $em = $this->getDoctrine()->getManager();
        $inv = $this->get('gist_inventory');
        
        $wh = $em->getRepository('GistInventoryBundle:Warehouse')->find($wh_id);
        $inv_account = $wh->getInventoryAccount();
        $stock = $em->getRepository('GistInventoryBundle:Stock')->findBy(array('inv_account'=>$inv_account->getID()));

        $products = array();
        foreach ($stock as $s) 
        {
            $products[] = [
            'prod_name' => $s->getProduct()->getName(),
            'prod_id' => $s->getProduct()->getID(),
            'uom' => $s->getProduct()->getUnitOfMeasure(),
            'stock' => $s->getQuantity(),
            ];
            
        }


        return new JsonResponse($products);   
    }
}
