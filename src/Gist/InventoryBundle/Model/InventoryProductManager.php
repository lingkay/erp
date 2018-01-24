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

class InventoryProductManager
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
    }

    public function getPOSProductCategoryOptions()
    {
        $filter = array();
        $categories = $this->em
            ->getRepository('GistInventoryBundle:ProductCategory')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $cat_opts = array();
        $cat_opts[''] = 'All';
        foreach ($categories as $category)
            $cat_opts[$category->getID()] = $category->getName();

        return $cat_opts;
    }

    public function getPOSProductOptions()
    {

    }
}

