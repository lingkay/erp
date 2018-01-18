<?php

namespace Gist\InventoryBundle\Model;

use Gist\InventoryBundle\Entity\StockTransferEntry;
use Gist\ValidationException;
use Gist\ConfigurationBundle\Model\ConfigurationManager;
use Doctrine\ORM\EntityManager;
use DateTime;

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
        $this->user = $security->getToken()->getUser();
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
        $wh_src = $this->identifyLocationInventoryAccount($data['source']);
        $wh_destination = $this->identifyLocationInventoryAccount($data['destination']);

        $o->setStatus('requested');
        $o->setRequestingUser($user);

        $o->setDescription($data['description']);
        $o->setSource($wh_src->getInventoryAccount());
        $o->setDestination($wh_destination->getInventoryAccount());

        $this->em->persist($o);
        $this->em->flush();

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
        $o->setStatus($data['status']);

        if($data['status'] == 'processed') {

            $o->setProcessedUser($user);
            $o->setDateProcessed(new DateTime());

            $entries = $this->updateEntries($data, $o, 'setProcessedQuantity');
            return $entries;

        } elseif ($data['status'] == 'delivered') {

            $user = $this->em->getRepository('GistUserBundle:User')->findOneBy(array('id'=>$data['selected_user']));
            $o->setDeliverUser($user);
            $o->setDateDelivered(new DateTime());

        } elseif ($data['status'] == 'arrived') {

            $o->setReceivingUser($user);
            $o->setDateReceived(new DateTime());

            $entries = $this->updateEntries($data, $o, 'setReceivedQuantity');
            return $entries;
        }
    }

    /**
     *
     * Remove stock transfer form entries
     *
     * @param $object
     */
    private function removeEntries($object)
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
            $qty = $data['processed_quantity'][$index];

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
    public function getPOSFormData($repo, $id, $pos_loc_id)
    {
        $st = $this->em->getRepository('GistInventoryBundle:StockTransfer')->findOneBy(array('id'=>$id));
        $pos_location = $this->em->getRepository('GistLocationBundle:POSLocations')->findOneBy(array('id'=>$pos_loc_id));
        $pos_iacc_id = $pos_location->getInventoryAccount()->getID();
        $list_opts = [];

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

