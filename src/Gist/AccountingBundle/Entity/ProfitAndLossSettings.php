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
 * @ORM\Table(name="acct_pl_settings")
 */

class ProfitAndLossSettings
{
    use HasGeneratedID;
    use TrackCreate;

    // NETSALES
    const TYPE_SALES  = "Sales";
    const TYPE_RETURN = "Return";
    const TYPE_SR     = "Sales Return";
    
    // COST OF SALES
    const TYPE_COS = "Cost of Sales";

    // COST OF LABOR
    const TYPE_COL = "Cost of Labor";

    // OTHER EMPLOYEE COST
    const TYPE_OEC = "Other Employee Cost";

    // OPEX
    // CONTROLLABLE EXPENSES
    const TYPE_UC   = "Utility Cost";
    const TYPE_TCDC = "Telephone Charges and DSL Connection";
    const TYPE_SUPP = "Supplies";
    const TYPE_RM   = "Repair and Maintenance";
    const TYPE_FOO  = "Freight Out and Others";
    const TYPE_OEE  = "Other External Expenditures";
    
    // NON-CONTROLLABLE EXPENSES
    const TYPE_OC   = "Occupancy Cost";
    const TYPE_DA   = "Depreciation and Amortization";

    // SALES AND MARKETING
    const TYPE_MAB  = "Marketing Advertising and Branding";

    // OTHER INCOME AND CHARGES
    const TYPE_OIC  = "Other Income and Charges";

    // NET INCOME TAX
    const TYPE_NIT  = "Net Income and Tax";
    
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
