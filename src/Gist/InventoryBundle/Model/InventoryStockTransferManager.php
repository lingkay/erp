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
use Doctrine\ORM\EntityManager;
use DateTime;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Stock;

class InventoryStockTransferManager
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
        //$this->user = $security->getToken()->getUser();
    }

    public function getDamagedContainerInventoryAccount($id, $type)
    {
        $inv_account = 0;

        if ($type == 'warehouse') {
            $warehouse = $this->em->getRepository('GistInventoryBundle:Warehouse')->find($id);
            $warehouse_iacc = $warehouse->getInventoryAccount();
            $inv_account = $warehouse_iacc->getDamagedContainer();
        } elseif ($type == 'pos') {
            $pos = $this->em->getRepository('GistLocationBundle:POSLocations')->find($id);
            $pos_iacc = $pos->getInventoryAccount();
            $inv_account = $pos_iacc->getDamagedContainer();
        }

        return $inv_account;
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

        foreach ($entries as $entry) {
            $this->updateStock($entry);
        }

        // TODO: end doctrine transaction

        // TODO: unlock table
    }

    /**
     *
     * Save new stock transfer form
     *
     * @param $o
     * @param $data
     * @param $user
     * @return array
     * @throws ValidationException
     */
    public function saveNewForm($o, $data, $user)
    {
        if ($data['rollback_flag'] == 'false') {
            $wh_src = $this->identifyLocationInventoryAccount($data['source']);
            $wh_destination = $this->identifyLocationInventoryAccount($data['destination']);

            $o->setStatus('requested');
            $o->setRequestingUser($user);

            $o->setDescription($data['description']);
            $o->setSource($wh_src->getInventoryAccount());
            $o->setDestination($wh_destination->getInventoryAccount());

            $this->em->persist($o);
            $this->em->flush();
        }

        if ($data['rollback_flag'] == 'true') {
            $this->removeEntries($o);
        }

        $entries = $this->saveEntries($data, $o);

        $this->em->flush();
        return $entries;
    }

    /**
     *
     * Update stock transfer form
     *
     * @param $o
     * @param $data
     * @param $user
     * @return array
     */
    public function updateForm($o, $data, $user)
    {
        $config = new ConfigurationManager($this->container);
        $adjustment_container = $this->findWarehouse($config->get('gist_adjustment_warehouse'));
        $adjustment_account = $adjustment_container->getInventoryAccount();

        $o->setStatus($data['status']);

        if ($data['status'] == 'processed') {

            $o->setProcessedUser($user);
            $o->setDateProcessed(new DateTime());


            $entries = $this->updateEntries($data, $o, 'setProcessedQuantity');
            return $entries;

        } elseif ($data['status'] == 'delivered') {

            $o->setDeliverUser($user);
            $o->setDateDelivered(new DateTime());


            $rounds = 0;

            //generate transfer entries from source to virtual
            foreach ($data['st_entry'] as $index => $value) {

                $entries_x334 = array();
                $rounds++;
                $entry_id = $value;
                $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));

                // setup transaction
                $trans = new Transaction();
                $trans->setUserCreate($user)
                    ->setDescription('Stock transfer item '.$entry->getProduct()->getID().' with '.$entry->getQuantity().' qty successfully transferred to virtual container.');

                // add entries
                // entry for destination
                $wh_entry = new Entry();
                $wh_entry->setInventoryAccount($adjustment_account)
                    ->setProduct($entry->getProduct());

                // entry for source
                $adj_entry = new Entry();
                $adj_entry->setInventoryAccount($o->getSource())
                    ->setProduct($entry->getProduct());

                $old_qty = 0;
                $new_qty = $entry->getQuantity();

                // check if debit or credit
                if ($new_qty > $old_qty) {
                    $qty = $new_qty - $old_qty;
                    $wh_entry->setDebit($qty);
                    $adj_entry->setCredit($qty);
                } else {
                    $qty = $old_qty - $new_qty;
                    $wh_entry->setCredit($qty);
                    $adj_entry->setDebit($qty);
                }
                $entries_x334[] = $wh_entry;
                $entries_x334[] = $adj_entry;

                foreach ($entries_x334 as $ent)
                    $trans->addEntry($ent);

                $this->persistTransaction($trans);
                $this->em->flush();
            }

        } elseif ($data['status'] == 'arrived') {

            $o->setReceivingUser($user);
            $o->setDateReceived(new DateTime());

            //generate transfer entries from virtual to destination
            foreach ($data['st_entry'] as $index => $value) {
                $entries = array();
                $entry_id = $value;
                $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));

                // setup transaction
                $trans = new Transaction();
                $trans->setUserCreate($user)
                    ->setDescription('Stock transfer items successfully delivered.');

                // add entries
                // entry for destination
                $wh_entry = new Entry();
                $wh_entry->setInventoryAccount($o->getDestination())
                    ->setProduct($entry->getProduct());

                // entry for source
                $adj_entry = new Entry();
                $adj_entry->setInventoryAccount($adjustment_account)
                    ->setProduct($entry->getProduct());

                $old_qty = 0;
                $new_qty = $entry->getQuantity();

                // check if debit or credit
                if ($new_qty > $old_qty) {
                    $qty = $new_qty - $old_qty;
                    $wh_entry->setDebit($qty);
                    $adj_entry->setCredit($qty);
                } else {
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


            $entriesx = $this->updateEntries($data, $o, 'setReceivedQuantity');
            return $entriesx;
        } elseif ($data['status'] == 'requested') {

            $o->setRequestingUser($user);
            $o->setDateCreate(new DateTime());

            $entries = $this->updateEntries($data, $o, 'setQuantity');
            return $entries;
        }
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
        if ($stock == null) {
            $stock = new Stock($account, $prod, $qty);

            // persist the new stock object
            $this->em->persist($stock);
        } else {
            // add quantity
            $old_qty = $stock->getQuantity();
            $new_qty = bcadd($qty, $old_qty, 2);
            $stock->setQuantity($new_qty);
        }


    }

    public function updatePOSForm($user, $entries, $id)
    {
        $o = $this->em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id' => $id));
        //$o->setStatus($data['status']);

        if ($o->getStatus() == 'requested') {
            $status = '';
        } elseif ($o->getStatus() == 'processed') {
            $status = 'requested';
        } elseif ($o->getStatus() == 'delivered') {
            $status = 'processed';
        } else {
            $status = '';
        }

        if ($status == '') {

            $o->setProcessedUser($user);
            $o->setDateProcessed(new DateTime());

            $entries = $this->updatePOSEntries($o, $entries, '');
            return $entries;

        } elseif ($status == 'requested') {

//            $user = $this->em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$data['selected_user']));
//            $o->setDeliverUser($user);
//            $o->setDateDelivered(new DateTime());

        } elseif ($status == 'arrived') {

            $o->setReceivingUser($user);
            $o->setDateReceived(new DateTime());

            $entries = $this->updatePOSEntries($o, $entries);
            return $entries;
        }
    }

    public function updatePOSEntries($st, $entries, $status)
    {
        parse_str($entries, $entriesParsed);
        foreach ($entriesParsed as $e) {
            $prod_item_code = $e['code'];
            $qty = $e['quantity'];
            $prod = $this->em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code' => $prod_item_code));
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            //from src
            $entry = new StockTransferEntry();
            $entry->setStockTransfer($st);
            $entry->setProduct($prod);

            if ($status == '') {
                $entry->setQuantity($qty);
            } elseif ($status == 'requested') {
                $entry->setProcessedQuantity($e['processed_quantity']);
            } elseif ($status == 'arrived') {
                $entry->setReceivedQuantity($e['received_quantity']);
            }

            $this->em->persist($entry);
            $this->em->flush();
        }

        return 0;
    }

    public function updateStockTransferEntriesRequested($entries, $st)
    {
        foreach ($entries as $e) {
            $qty = $e['quantity'];
            $prod = $this->em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code' => $e['code']));
            if ($prod == null)
                throw new ValidationException('Could not find product.');

            $entry = new StockTransferEntry();
            $entry->setStockTransfer($st)
                ->setProduct($prod)
                ->setQuantity($qty);

            $this->em->persist($entry);
        }
    }

    public function updateStockTransferEntriesArrived($entries, $st)
    {
        foreach ($entries as $e) {
            if (isset($e['st_entry'])) {
                $entry_id = $e['st_entry'];
                $qty = $e['received_quantity'];
                $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));
                $entry->setReceivedQuantity($qty);
                $this->em->persist($entry);
                $this->em->flush();
            }
        }
    }

    public function updateStockTransferEntriesProcessed($entries, $st)
    {
        foreach ($entries as $e) {
            if (isset($e['st_entry'])) {
                $entry_id = $e['st_entry'];
                $qty = $e['processed_quantity'];
                $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id' => $entry_id));
                $entry->setProcessedQuantity($qty);
                $this->em->persist($entry);
                $this->em->flush();
            }
        }
    }

    /**
     *
     * Remove stock transfer form entries
     *
     * @param $object
     */
    public function removeEntries($object)
    {
        foreach ($object->getEntries() as $entry) {
            $this->em->remove($entry);
        }

        $this->em->flush();
    }

    /**
     *
     * Save new stock transfer form entries
     *
     * @param $data
     * @param $object
     * @return array
     * @throws ValidationException
     */
    private function saveEntries($data, $object)
    {
        $entries = array();

        foreach ($data['product_item_code'] as $index => $value)
        {
            $prod_item_code = $value;
            $qty = $data['quantity'][$index];

            $prod = $this->em->getRepository('GistInventoryBundle:Product')->findOneBy(array('item_code'=>$prod_item_code));
            if ($prod == null)
                throw new ValidationException('Could not find product.');


            $entry = new StockTransferEntry();
            $entry->setStockTransfer($object)
                ->setProduct($prod)
                ->setQuantity($qty);

            $this->em->persist($entry);
            $entries[] = $entry;
        }

        return $entries;
    }

    /**
     *
     * Update stock transfer form entries
     *
     * @param $data
     * @param $object
     * @param $setter
     * @return array
     */
    public function updateEntries($data, $object, $setter)
    {
        $entries = array();

        foreach ($data['st_entry'] as $index => $value)
        {

            $entry_id = $value;


            if ($data['status'] == 'arrived') {
                $qty = $data['received_quantity'][$index];
            } elseif ($data['status'] == 'requested') {
                $qty = $data['quantity'][$index];
            } else {
                $qty = $data['processed_quantity'][$index];
            }

            $entry = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findOneBy(array('id'=>$entry_id));
            $entry->$setter($qty);

            $this->em->persist($entry);
            $entries[] = $entry;

        }

        $this->em->flush();
        return $entries;
    }

    public function getPOSFormDataEntries($repo, $id)
    {
        $st = $this->em->getRepository('GistInventoryBundle:StockTransferEntry')->findBy(array('stock_transfer'=>$id));

        $list_opts = [];
        foreach ($st as $p) {

            $list_opts[] = array(
                'id'=>$p->getID(),
                'item_code'=>$p->getProduct()->getItemCode(),
                'product_name'=> $p->getProduct()->getName(),
                'quantity'=> $p->getQuantity(),
                'processed_quantity'=> $p->getProcessedQuantity(),
                'received_quantity'=> $p->getReceivedQuantity(),
            );

        }

        return $list_opts;
    }

    /**
     *
     * Find warehouse by ID
     *
     * @param $id
     * @return mixed
     */
    public function findWarehouse($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Warehouse')->find($id);
    }

    /**
     *
     * Find POS Location by ID
     *
     * @param $id
     * @return mixed
     */
    public function findPOSLocation($id)
    {
        return $this->em->getRepository('GistLocationBundle:POSLocations')->find($id);
    }

    /**
     *
     * Identify if given POS Location ID is a valid location
     * if not return main warehouse
     *
     * @param $locationID
     * @param null $type
     * @return mixed
     */
    public function identifyLocationInventoryAccount($locationID, $type = null)
    {
        $config = new ConfigurationManager($this->container);

        if ($locationID == 0) {

            $wh_src = $this->findWarehouse($config->get('gist_main_warehouse'));

        } else {

            $wh_src = $this->findPOSLocation($locationID);

        }

        return $wh_src;
    }

    /**
     *
     * Get stock transfer form data for POS
     *
     * @param $repo
     * @param $id
     * @param $pos_loc_id
     * @return array
     */
    public function getPOSFormData($repo, $id, $pos_loc_id, $rollback = false)
    {
        $st = $this->em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
        $pos_location = $this->em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $pos_iacc_id = $pos_location->getInventoryAccount()->getID();
        $list_opts = [];
        $main_status = $st->getStatus();

        if ($rollback) {
            if ($st->getStatus() == 'requested') {
                $main_status = '';
            } elseif ($st->getStatus() == 'processed') {
                $main_status = 'requested';
            } elseif ($st->getStatus() == 'delivered') {
                $main_status = 'processed';
            }
        }

        if ($st->getSource()->getID() == $pos_iacc_id || $st->getDestination()->getID() == $pos_iacc_id) {

            $list_opts[] = array(
                'id'=>$st->getID(),
                'source'=> $st->getSource()->getName(),
                'source_id'=> $st->getSource()->getID(),
                'destination'=> $st->getDestination()->getName(),
                'destination_id'=> $st->getDestination()->getID(),
                'pos_iacc_id' => $pos_iacc_id,
                'date_create'=> $st->getDateCreate()->format('y-m-d H:i:s'),
                'status'=> $st->getStatus(),
                'statusFMTD'=> $st->getStatusFMTD(),
                'main_status'=> $main_status,
                'description'=> $st->getDescription(),
                'user_create' => $st->getRequestingUser()->getDisplayName(),
                'user_processed' => ($st->getProcessedUser() == null ? '-' : $st->getProcessedUser()->getDisplayName()),
                'user_delivered' => ($st->getDeliverUser() == null ? '-' : $st->getDeliverUser()->getDisplayName()),
                'user_received' => ($st->getReceivingUser() == null ? '-' : $st->getReceivingUser()->getDisplayName()),
                'date_processed' => ($st->getDateProcessed() == null ? '' : $st->getDateProcessed()->format('y-m-d H:i:s')),
                'date_delivered' => ($st->getDateDelivered() == null ? '' : $st->getDateDelivered()->format('y-m-d H:i:s')),
                'date_received' => ($st->getDateReceived() == null ? '' : $st->getDateReceived()->format('y-m-d H:i:s')),
                'invalid'=>'false',
            );

        } else {

            $list_opts[] = array(
                'id'=>0,
                'source'=> 0,
                'destination'=> 0,
                'date_create'=> 0,
                'status'=> 0,
                'description'=> 0,
                'user_create' => 0,
                'user_processed' => 0,
                'user_delivered' => 0,
                'user_received' => 0,
                'date_processed' => 0,
                'date_delivered' => 0,
                'date_received' => 0,
                'invalid'=>'true',
            );

        }

        return $list_opts;
    }
}

