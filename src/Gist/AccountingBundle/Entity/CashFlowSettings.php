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
 * @ORM\Table(name="acct_cashflow_settings")
 */

class CashFlowSettings
{
    use HasGeneratedID;
    use TrackCreate;

    const TYPE_AR  = "Accounts Receivable";
    const TYPE_PE  = "Prepaid Expenses";
    const TYPE_AP  = "Accounts Payable";
    const TYPE_DEP = "Depreciation";
    const TYPE_IA  = "Investing Activities";
    const TYPE_FA  = "Financing Activities";

    /**
     * @ORM\ManyToOne(targetEntity="\Gist\AccountingBundle\Entity\ChartOfAccount")
     * @ORM\JoinColumn(name="main_account_id", referencedColumnName="id", nullable=true)
     */
    protected $coa;

    /** @ORM\Column(type="string", length=150) */
    protected $type;

    public function __construct()
    {
        $this->initTrackCreate();
    }

    public function setAccount($account)
    {
        $this->coa = $account;
        return $this;
    }

    public function getAccount()
    {
        return $this->coa;
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
