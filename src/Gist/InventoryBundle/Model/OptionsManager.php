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

class OptionsManager
{
    protected $em;
    protected $container;

    public function __construct(EntityManager $em, $container = null, $security = null)
    {
        $this->em = $em;
        $this->container = $container;
    }

    public function getItemOptions($filter = array())
    {
        $whs = $this->em->getRepository('GistInventoryBundle:Product')->findAll();

        $wh_opts = array();
        foreach ($whs as $wh)
            $wh_opts[$wh->getID()] = $wh->getName();

        return $wh_opts;
    }


}
