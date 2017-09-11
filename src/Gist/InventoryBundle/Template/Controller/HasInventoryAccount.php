<?php

namespace Gist\InventoryBundle\Template\Controller;

use Gist\InventoryBundle\Entity\Account;

trait HasInventoryAccount
{
    protected function createInventoryAccount($o)
    {
        $allow = false;

        // override this if you want a non-blank name and different negative allow settings
        $account = new Account();
        $account->setName('')
            ->setUserCreate($this->getUser())
            ->setAllowNegative($allow);

        return $account;
    }

    protected function updateHasInventoryAccount($o, $data, $is_new)
    {
        if ($is_new)
        {
            // create inventory account for new objects
            $account = $this->createInventoryAccount($o, $data);

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
            $o->setInventoryAccount($account);
        }

        /*
            // TERRIBLE implementation since it has to be updated for each child
            // loses all the benefits of having traits
            // should be the other way around
            $allow = false;
            if($this->newBaseClass() instanceof Warehouse)
            {
                $prefix = 'Warehouse: ';
                $allow = false;
            }
            else if($this->newBaseClass() instanceof Supplier){
                $prefix = 'Supplier: ';
                $allow = true;
            }
            if($this->newBaseClass() instanceof Department)
            {
                $prefix = 'Department: ';
                $allow = true;
            }
                        
           
            $account = new Account();
            $account->setAllowNegative($allow)
                ->setUserCreate($this->getUser())
                ->setName($prefix . $o->getName());

            $em = $this->getDoctrine()->getManager();
            $em->persist($account);
            $em->flush();
            $o->setInventoryAccount($account);
        }
        */
    }
}
