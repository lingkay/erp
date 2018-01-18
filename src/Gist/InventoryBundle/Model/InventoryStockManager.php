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

class InventoryStockManager
{
    protected $em;
    protected $container;
    protected $user;

    public function __construct(EntityManager $em, $container = null, $security = null)
    {
        $this->em = $em;
        $this->container = $container;
        $this->user = $security->getToken()->getUser();
    }


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
}
