<?php

namespace Gist\InventoryBundle\Model;

use Gist\InventoryBundle\Entity\Product;
use Gist\InventoryBundle\Entity\ProductAttribute;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Stock;
use Gist\InventoryBundle\Entity\Account;
use Gist\ValidationException;
use Gist\ConfigurationBundle\Model\ConfigurationManager;
use Doctrine\ORM\EntityManager;

class StockManipulationManager
{
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, $container = null, $security = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function performTransfer($entries, $st, $user, $type = 'ERP')
    {
        $config = new ConfigurationManager($this->container);
        $adjustment_container = $this->findWarehouse($config->get('gist_adjustment_warehouse'));
        $adjustment_account = $adjustment_container->getInventoryAccount();


        foreach ($entries as $e) {
            $entries_x = array();
            if ($type == 'POS') {
                $entry_id = $e['st_entry'];
                $qty = $e['received_quantity'];
            } else {
                $entry_id = $e->getID();
                $qty = $e->getQuantity();
            }


            $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));
            $prod = $entry->getProduct();

            $trans = new Transaction();
            $trans->setUserCreate($user)
                ->setDescription('POS VIRTUAL TO DEST');


            // entry for destination
            $destination_entry = new Entry();
            $destination_entry->setInventoryAccount($st->getDestination())
                ->setProduct($prod);

            // entry for source
            $source_entry = new Entry();
            $source_entry->setInventoryAccount($adjustment_account)
                ->setProduct($prod);

            $old_qty = 0;
            $new_qty = $qty;

            // check if debit or credit
            if ($new_qty > $old_qty)
            {
                $qty = $new_qty - $old_qty;
                $destination_entry->setDebit($qty);
                $source_entry->setCredit($qty);
            }
            else
            {
                $qty = $old_qty - $new_qty;
                $destination_entry->setCredit($qty);
                $source_entry->setDebit($qty);
            }
            $entries_x[] = $destination_entry;
            $entries_x[] = $source_entry;

            foreach ($entries_x as $ent)
                $trans->addEntry($ent);

            $this->persistTransaction($trans);
            $this->em->flush();

        }
    }

    public function performTransferToVirtual($entries, $st, $user, $type = 'ERP')
    {
        $config = new ConfigurationManager($this->container);
        $adjustment_container = $this->findWarehouse($config->get('gist_adjustment_warehouse'));
        $adjustment_account = $adjustment_container->getInventoryAccount();


        foreach ($entries as $e) {
            $entries_x = array();
            if ($type == 'POS') {
                $entry_id = $e['st_entry'];
                $qty = $e['processed_quantity'];
            } else {
                $entry_id = $e->getID();
                $qty = $e->getQuantity();
            }


            $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));
            $prod = $entry->getProduct();

            $trans = new Transaction();
            $trans->setUserCreate($user)
                ->setDescription('POS SRC TO VIRTUAL');


            // entry for destination
            $destination_entry = new Entry();
            $destination_entry->setInventoryAccount($adjustment_account)
                ->setProduct($prod);

            // entry for source
            $source_entry = new Entry();
            $source_entry->setInventoryAccount($st->getSource())
                ->setProduct($prod);

            $old_qty = 0;
            $new_qty = $qty;

            // check if debit or credit
            if ($new_qty > $old_qty)
            {
                $qty = $new_qty - $old_qty;
                $destination_entry->setDebit($qty);
                $source_entry->setCredit($qty);
            }
            else
            {
                $qty = $old_qty - $new_qty;
                $destination_entry->setCredit($qty);
                $source_entry->setDebit($qty);
            }
            $entries_x[] = $destination_entry;
            $entries_x[] = $source_entry;

            foreach ($entries_x as $ent)
                $trans->addEntry($ent);

            $this->persistTransaction($trans);
            $this->em->flush();

        }
    }

    public function persistTransaction(Transaction $trans)
    {
        // check balance
        if (!$trans->checkBalance())
            throw new InventoryException('Inventory transaction unbalanced. Incoming entries must be equivalent to outgoing entries.');

        $this->em->persist($trans);
        $entries = $trans->getEntries();

        foreach ($entries as $entry)
        {
            $this->updateStock($entry);
        }
    }

    public function updateStock(Entry $entry)
    {
        $account = $entry->getInventoryAccount();
        $prod = $entry->getProduct();

        $qty = bcsub($entry->getDebit(), $entry->getCredit(), 2);

        $stock_repo = $this->em->getRepository('GistInventoryBundle:Stock');
        $stock = $stock_repo->findOneBy(array('inv_account' => $account, 'product' => $prod));
        if ($stock == null)
        {
            $stock = new Stock($account, $prod, $qty);
            $this->em->persist($stock);
        }
        else
        {
            $old_qty = $stock->getQuantity();
            $new_qty = bcadd($qty, $old_qty, 2);
            $stock->setQuantity($new_qty);
        }
    }

    public function findWarehouse($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Warehouse')->find($id);
    }
}

