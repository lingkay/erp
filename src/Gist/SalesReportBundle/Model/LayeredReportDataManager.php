<?php

namespace Gist\SalesReportBundle\Model;

use Gist\InventoryBundle\Entity\StockTransferEntry;
use Gist\ValidationException;
use Gist\ConfigurationBundle\Model\ConfigurationManager;
use Doctrine\ORM\EntityManager;
use DateTime;
use Gist\InventoryBundle\Entity\Entry;
use Gist\InventoryBundle\Entity\Transaction;
use Gist\InventoryBundle\Entity\Stock;

class LayeredReportDataManager
{
    protected $em;
    protected $container;
    protected $user;

    /**
     * LayeredReportDataManager constructor.
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

    public function getTransactions($dateFrom, $dateTo, $reportType, $args)
    {
        $query = $this->em->createQueryBuilder();
        $query->from('GistPOSERPBundle:POSTransaction', 'o')
            ->where('o.date_create <= :date_to')
            ->andWhere('o.date_create >= :date_from')
            ->setParameter('date_from', $dateFrom. ' 00:00:00')
            ->setParameter('date_to', $dateTo.' 23:59:59');

        $data = $query->select('o')
            ->getQuery()
            ->getResult();

        return $data;
    }

    public function getTransactionItems($dateFrom, $dateTo, $reportType, $args)
    {
        $query = $this->em->createQueryBuilder();
        $query->from('GistPOSERPBundle:POSTransactionItem', 'o')
            ->join('GistPOSERPBundle:POSTransaction', 't', 'WITH', 't.id= o.transaction')
            ->where('o.date_create <= :date_to')
            ->andWhere('o.date_create >= :date_from')
            ->setParameter('date_from', $dateFrom. '00:00:00')
            ->setParameter('date_to', $dateTo.' 23:59:59');

        $transactionItems = $query->select('o')
            ->getQuery()
            ->getResult();

        return $transactionItems;
    }
}

