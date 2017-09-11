<?php

namespace Gist\InventoryBundle\Controller;

use Gist\TemplateBundle\Model\BaseController;
use Gist\InventoryBundle\Entity\Stock;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Model\InventoryException;
use Gist\ValidationException;
//TEST
class TransactionController extends BaseController
{
    public function indexAction()
    {
        $this->title = 'Transfer Stock';
        $params = $this->getViewParams('', 'cat_inv_trans_index');

        $inv = $this->get('gist_inventory');
        $params['wh_opts'] = $inv->getWarehouseOptions();
        $params['prod_opts'] = $inv->getProductOptions();

        return $this->render('GistInventoryBundle:Transaction:index.html.twig', $params);
    }

    protected function processTransferEntries($data, $prefix)
    {
        $em = $this->getDoctrine()->getManager();

        // figure out setter
        if ($prefix == 'from')
            $setter = 'setCredit';
        else
            $setter = 'setDebit';

        // initialize entries
        $entries = array();

        // check if there's anything to process
        if (!isset($data[$prefix . '_wh_id']))
            return $entries;

        // process it
        foreach ($data[$prefix . '_wh_id'] as $index => $wh_id)
        {
            $prod_id = $data[$prefix . '_prod_id'][$index];
            $qty = $data[$prefix . '_qty'][$index];

            // product
            $prod = $em->getRepository('GistInventoryBundle:Product')->find($prod_id);
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            // warehouse
            $wh = $em->getRepository('GistInventoryBundle:Warehouse')->find($wh_id);
            if ($wh == null)
                throw new ValidationException('Could not find warehouse.');

            $entry = new Entry();
            $entry->setWarehouse($wh)
                ->setProduct($prod)
                ->$setter($qty);

            $entries[] = $entry;
        }

        return $entries;
    }

    public function addSubmitAction()
    {
        $inv = $this->get('gist_inventory');
        $log = $this->get('gist_log');

        $em = $this->getDoctrine()->getManager();

        try
        {
            $data = $this->getRequest()->request->all();

            // process entries
            $from_ents = $this->processTransferEntries($data, 'from');
            $to_ents = $this->processTransferEntries($data, 'to');

            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($this->getUser())
                ->setDescription($data['desc']);

            // add entries
            foreach ($from_ents as $ent)
                $trans->addEntry($ent);
            foreach ($to_ents as $ent)
                $trans->addEntry($ent);

            $inv->persistTransaction($trans);
            $em->flush();

            // log
            $odata = $trans->toData();
            $log->log('cat_inv_trans_add', 'added Inventory Transaction ' . $trans->getID() . '.', $odata);
        }
        catch (ValidationException $e)
        {
            $this->addFlash('error', $e->getMessage());

            return $this->redirect($this->generateUrl('cat_inv_trans_index'));
        }
        catch (InventoryException $e)
        {
            $this->addFlash('error', $e->getMessage());

            return $this->redirect($this->generateUrl('cat_inv_trans_index'));
        }

        $this->addFlash('success', 'Transfer transaction successful.');
        return $this->redirect($this->generateUrl('cat_inv_trans_index'));
    }
}
