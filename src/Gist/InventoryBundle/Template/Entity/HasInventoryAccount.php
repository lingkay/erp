<?php

namespace Gist\InventoryBundle\Template\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\InventoryBundle\Entity\Account;

trait HasInventoryAccount
{
    /**
     * @ORM\ManyToOne(targetEntity="\Gist\InventoryBundle\Entity\Account")
     * @ORM\JoinColumn(name="inv_account_id", referencedColumnName="id")
     */
    protected $inv_account;

    protected function initHasInventoryAccount()
    {
        $this->inv_account = null;
    }

    public function setInventoryAccount(Account $account)
    {
        $this->inv_account = $account;
        return $this;
    }

    public function getInventoryAccount()
    {
        return $this->inv_account;
    }

    public function dataHasInventoryAccount($data)
    {
        if ($this->inv_account == null)
            $data->inv_account = null;
        else
            $data->inv_account = $this->inv_account->toData();
    }
}
