<?php

namespace Gist\AccountingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasNotes;
use Gist\CoreBundle\Template\Entity\HasName;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\HasType;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\CoreBundle\Template\Entity\HasStatus;
use Gist\AccountingBundle\Entity\ChartOfAccount;


// use Hris\ToolsBundle\Entity\EmployeeAdvanceEntry;

use DateTime;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_mainaccounts")
 */
class MainAccount
{
    use HasGeneratedID;
    use HasName;
    use HasCode;
    use TrackCreate;
    use HasStatus;

    /** @ORM\Column(type="string", length=80, nullable=true) */
    protected $last_code;

    /**
     * @ORM\OneToMany(targetEntity="\Gist\AccountingBundle\Entity\ChartOfAccount", mappedBy="main_account", cascade={"persist"})
     */
    protected $coa;

    public function __construct()
    {
        $this->initHasName();
        $this->initTrackCreate();
        $this->initHasCode();
        $this->last_code = "0000";
    }


    public function setLastCode($last_code)
    {
        $this->last_code = $last_code;
        return $this;
    }

    public function getLastCode()
    {
        return $this->last_code;
    }

    public function getNameCode()
    {
        return $this->name." (".$this->code.")";
    }

    public function getChartOfAccounts()
    {
        return $this->coa;
    }

    public function addChartOfAccount(ChartOfAccount $chart_of_account)
    {
        $chart_of_account->setMainAccount($this);
        $this->coa->add($chart_of_account);
        return $this;
    }

    public function setPack($pack)
    {
        $this->coa = $pack;
        return $this;
    }

    public function getPack()
    {
        return $this->pack;
    }
    
    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
