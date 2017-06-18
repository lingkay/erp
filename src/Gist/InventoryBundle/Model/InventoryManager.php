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

class InventoryManager
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

    public function getSupplierTypeOptions()
    {
        return array(
            'ind' => 'Individual',
            'cmp' => 'Company'
        );
    }

    public function getSupplierCategoryOptions()
    {
        return array(
            'Supplies' => 'Supplies',
            'Loan' => 'Loan',
            'Sub Distributors' => 'Sub Distributors',
            'Publisher' => 'Publisher',
            'Courier/Forwarder' => 'Courier/Forwarder',
            'Account Executive' => 'Account Executive',
            'service' => 'service',
            'Manpower' => 'Manpower',
            'Rent' => 'Rent',
            'Others' => 'Others'
        );
    }

    public function getSupplierTaxOptions()
    {
        return array(
            '1' => 'Non-vatable (0%)',
            '2' => 'Vatable (12%)',
            '3' => 'Zero-rated (0%)'
        );
    }

    public function newEntry()
    {
        $entry = new Entry();
        return $entry;
    }

    public function newTransaction()
    {
        $trans = new Transaction();
        return $trans;
    }

    public function findWarehouse($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Warehouse')->find($id);
    }

    public function findProductGroup($id)
    {
        return $this->em->getRepository('GistInventoryBundle:ProductGroup')->find($id);
    }

    public function findBrand($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Brand')->find($id);
    }

    public function findProduct($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Product')->find($id);
    }

    public function findProductByName($name)
    {
        $product = $this->em->getRepository('GistInventoryBundle:Product')->findOneByName($name);
        
        if ($product != null)
        {
            return $product;
        }
        else
        {
            return null;
        }
    }

    public function findInventoryAccount($id)
    {
        return $this->em->getRepository('GistInventoryBundle:Account')->find($id);
    }
    
    public function getProductStock($wh_inv_acc, $product)
    {
        return $this->em->getRepository('GistInventoryBundle:Stock')->findOneBy(array(   
                    'inv_account' => $wh_inv_acc,
                    'product' => $product
                ));

    }

    public function getSupplierOptions($filter = array())
    {
        // $whs = $this->em
        //     ->getRepository('x:Supplier')
        //     ->findBy(
        //         $filter
        //     );

        // $wh_opts = array();
        // foreach ($whs as $wh)
        //     $wh_opts[$wh->getID()] = $wh->getNameFormatted();

        return null;
    }

    public function getWarehouseOptions($filter = array())
    {
        $whs = $this->em
            ->getRepository('GistInventoryBundle:Warehouse')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $wh_opts = array();
        foreach ($whs as $wh)
            $wh_opts[$wh->getID()] = $wh->getName();

        return $wh_opts;
    }

    public function getTankOptions($filter = array())
    {
        $whs = $this->em
            ->getRepository('GistInventoryBundle:Warehouse')
            ->findBy(array('type'=>'tank')
            );

        $wh_opts = array();
        foreach ($whs as $wh)
            $wh_opts[$wh->getID()] = $wh->getName();

        return $wh_opts;
    }


    public function getWarehouseOptionsByType($type, $index_type=null)
    {
        $whs = $this->em
            ->getRepository('GistInventoryBundle:Warehouse')
            ->findBy(array('type'=>$type));

        $wh_opts = array();
        foreach ($whs as $wh)
        {
            if ($index_type == 'inv_account') 
            {
                $wh_opts[$wh->getInventoryAccount()->getID()] = $wh->getName();
            }
            elseif ($index_type == 'id') 
            {
                $wh_opts[$wh->getID()] = $wh->getName();
            }
            else
            {
                $wh_opts[$wh->getName()] = $wh->getName();
            }
        }

        return $wh_opts;
    }

    public function getInventoryAccountWarehouseOptions($filter = array())
    {
        $whs = $this->em
            ->getRepository('GistInventoryBundle:Warehouse')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $wh_opts = array();
        foreach ($whs as $wh)
            $wh_opts[$wh->getInventoryAccount()->getID()] = $wh->getName();

        return $wh_opts;
    }

    public function getInventoryAccountDepartmentOptions($filter = array())
    {
        $depts = $this->em
            ->getRepository('GistUserBundle:Department')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $dept_opts = array();
        foreach($depts as $dept)
            $dept_opts[$dept->getInventoryAccount()->getID()] = $dept->getName();

        return $dept_opts;
    }

    public function getProductGroupOptions($filter = array())
    {
        $pgs = $this->em
            ->getRepository('GistInventoryBundle:ProductGroup')
            ->findBy(
                $filter,
                array('name' => 'ASC')
            );

        $pg_opts = array();
        foreach ($pgs as $pg)
            $pg_opts[$pg->getID()] = $pg->getName();

        return $pg_opts;
    }

    public function getProductVariantsOption($product){
        $products = $product->getVariants();
        $prod_opts = array();

        foreach ($products as $prod)
            $prod_opts[$prod->getID()] = $prod->getName();

        return $prod_opts;
    }
    
    public function getProductOptions($filter = array())
    {
        $filter = array_merge($filter, array('parent'=> null));
        $products = $this->em
            ->getRepository('GistInventoryBundle:Product')
            ->findBy(
                $filter,
                array('sku' => 'ASC')
            );

        $prod_opts = array();
        foreach ($products as $prod)
            $prod_opts[$prod->getID()] = $prod->getName();

        return $prod_opts;
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
    
    public function newVariant(Product $parent, ProductAttribute $attribute)
    {
        $new = clone $parent;
        $new->addVariantAttribute($attribute);
        
        // TODO  This part should be moved somewhere else
        $new->generateSku();
        $attribute->setProduct($new);
        $parent->addVariant($new);
        
        $this->em->persist($new);
        $this->em->persist($parent);
        $this->em->flush();
                
        return $new;
    }
    
    // TODO: remove this
    // inventory service should not be reliant on main warehouse config
    // 01-25-16: add warehouse as paramater?
    public function itemsIn($product, $quantity,$supplier, $warehouse)
    {

        
        $conf = new ConfigurationManager($this->container);
        $to = $warehouse->getInventoryAccount();
        $from = $supplier->getInventoryAccount();
        
        return $this->itemTransfer($product, $quantity, $from, $to);
    }

    public function itemsOut($product, $quantity,$supplier,$warehouse){
        $conf = new ConfigurationManager($this->container);
        $from = $warehouse->getInventoryAccount();
        $to = $supplier->getInventoryAccount();
        
        return $this->itemTransfer($product, $quantity, $from, $to);
    }
    
    // TODO: remove this too. this can be done better
    public function itemTransfer($product,$quantity,$from,$to)
    {
        echo $product->getName().','. $quantity.','.$from->getName().','.$to->getName() . '<br />'; 
        $entryDebit = $this->newEntry();
        $entryCredit = $this->newEntry();
        
        $entryCredit->setProduct($product)
                    ->setInventoryAccount($from)
                    ->setCredit($quantity)
                    ->setDebit(0);
        
        $entryDebit->setProduct($product)
                    ->setInventoryAccount($to)
                    ->setDebit($quantity)
                    ->setCredit(0);
        
        return [$entryCredit, $entryDebit];
    }
    
    public function getWarehouseStock($warehouse)
    {
        $stock = $this->em->getRepository('GistInventoryBundle:Stock')
                ->findByAccount($warehouse->getInventoryAccount());
        
        return $stock;
    }

    public function getStockCount($inv_account, $product)
    {
        $stock = $this->em->getRepository('GistInventoryBundle:Stock')->find([
            'product' => $product,
            'inv_account' => $inv_account
        ]);

        if ($stock == null)
            $quantity = 0.00;
        else
            $quantity = $stock->getQuantity();

        return $quantity;
    }


    /**
     * 
     * @param type $warehouse
     * @param type $product
     * @return int
     * Returns the total quantity of a product and its variants
     */
    public function getStock( $warehouse, $product)
    {
        $stock = $this->em->getRepository('GistInventoryBundle:Stock')
                ->findOneBy(array('product' => $product,
                            'inv_account' => $warehouse->getInventoryAccount()));

        if($product->getVariants() == null){
            if($stock == null || empty($stock)){
                return 0;
            }else {
                    return $stock->getQuantity();
            }
        }else {
            $qty = $stock==null?0:$stock->getQuantity();
            foreach ($product->getVariants() as $variant){
                $qty += $this->getStock($warehouse, $variant);
            }
            return $qty;
        }
    }
}
