<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasType;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasStatus;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use DateTime;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_balance_settings")
 */

class TrialBalanceSettings
{
    use HasGeneratedID;
    use TrackCreate;

    const TYPE_ASSET        = "Asset";
    const TYPE_LIABILITY    = "Liability";
    const TYPE_CAPITAL      = "Capital";
    const TYPE_NET_SALES    = "Net Sales (Sales)";
    const TYPE_NET_REVENUE    = "Net Sales (Revenue)";
    const TYPE_COS          = "Cos";
    const TYPE_OPEX         = "Opex";

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\AccountingBundle\Entity\MainAccount")
     * @ORM\JoinColumn(name="main_account_id", referencedColumnName="id", nullable=true)
     */
    protected $main_account;

    /** @ORM\Column(type="string", length=150) */
    protected $type;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setAccount($account)
    {
        $this->main_account = $account;
        return $this;
    }

    public function getAccount()
    {
        return $this->main_account;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }
}
