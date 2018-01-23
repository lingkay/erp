<?php

/**
 *
 * NOTE FOR DEFAULTS
 * 0 - MAIN WAREHOUSE
 * 00 - MAIN DAMAGE WAREHOUSE
 *
 */
namespace Gist\InventoryBundle\Model;

use Gist\InventoryBundle\Entity\StockTransferEntry;
use Gist\ValidationException;
use Gist\ConfigurationBundle\Model\ConfigurationManager;
use Gist\InventoryBundle\Model\InventoryStockTransferManager;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Entry;
use Doctrine\ORM\EntityManager;
use DateTime;

class InventoryDamagedItemsManager
{
    protected $em;
    protected $container;
    protected $user;

    /**
     * InventoryStockTransferManager constructor.
     * @param EntityManager $em
     * @param null $container
     * @param null $security
     */
    public function __construct(EntityManager $em, $container = null, $security = null)
    {
        $this->em = $em;
        $this->container = $container;
        $this->user = $security->getToken()->getUser();
    }

    public function transferDamagedItemsToDestination($id, $type = NULL)
    {
        $config = new ConfigurationManager($this->container);
        $stManager = new InventoryStockTransferManager($this->em);
        $dmg = $this->em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));

        $dmg_acc = $dmg->getDestination()->getDamagedContainer();
        $adj_acc = $dmg->getSource()->getDamagedContainer();

        $remarks = $dmg->getDescription();

        foreach ($dmg->getEntries() as $entry) {

            $new_qty = $entry->getQuantity(); //get from entry
            $old_qty = 0;

            // setup transaction
            $trans = new Transaction();
            $trans->setUserCreate($this->user)
                ->setDescription($remarks);

            // add entries
            // entry for destination
            $wh_entry = new Entry();
            $wh_entry->setInventoryAccount($dmg_acc)
                ->setProduct($entry->getProduct());

            // entry for source
            $adj_entry = new Entry();
            $adj_entry->setInventoryAccount($adj_acc)
                ->setProduct($entry->getProduct());

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

            foreach ($entries as $ent)
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

        // TODO: lock table

        // TODO: check product stock / availability in source warehouse

        // TODO: start doctrine transaction

        // persist transaction
        $this->em->persist($trans);

        // update inventory stock
        $entries = $trans->getEntries();

        foreach ($entries as $entry)
        {
            $this->updateStock($entry);
        }

        // TODO: end doctrine transaction

        // TODO: unlock table
    }

    public function updateStock(Entry $entry)
    {
        // TODO: db row locking

        $account = $entry->getInventoryAccount();
        $prod = $entry->getProduct();

        $qty = bcsub($entry->getDebit(), $entry->getCredit(), 2);

        // get stock
        $stock_repo = $this->em->getRepository('GistInventoryBundle:Stock');
        $stock = $stock_repo->findOneBy(array('inv_account' => $account, 'product' => $prod));
        if ($stock == null)
        {
            $stock = new Stock($account, $prod, $qty);

            // persist the new stock object
            $this->em->persist($stock);
        }
        else
        {
            // add quantity
            $old_qty = $stock->getQuantity();
            $new_qty = bcadd($qty, $old_qty, 2);
            $stock->setQuantity($new_qty);
        }
    }

    public function updateDamagedEntriesStatus($id, $status)
    {
        $dmg = $this->em->getRepository('GistInventoryBundle:DamagedItems')->findOneBy(array('id'=>$id));
        foreach ($dmg->getEntries() as $e)
        {
            if ($e->getStatus() == 'damaged' && $status == 'for return') {
                $e->setStatus($status);
            } elseif ($e->getStatus() == 'for return' && $status == 'returned') {
                $e->setStatus($status);
            }

            $this->em->persist($e);
        }

        $this->em->flush();
    }


}

