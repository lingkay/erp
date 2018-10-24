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


// use Hris\ToolsBundle\Entity\EmployeeAdvanceEntry;

use DateTime;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="acct_accounts")
 */
class ChartOfAccount
{
    use HasGeneratedID;
    use HasName;
    use HasCode;
    use HasType;
    use HasNotes;
    use TrackCreate;
    use HasStatus;
  

    /**
     * @ORM\ManyToOne(targetEntity="Gist\AccountingBundle\Entity\MainAccount")
     * @ORM\JoinColumn(name="main_Account_id", referencedColumnName="id")
     */
    protected $main_account;

    public function __construct()
    {
        $this->initHasName();
        $this->initTrackCreate();
        $this->initHasCode();
    }

    public function getNameCode()
    {
        return $this->name." (".$this->code.")";
    }

    public function setMainAccount(MainAccount $main_account)
    {
        $this->main_account = $main_account;
        return $this;
    }

    public function getMainAccount()
    {
        return $this->main_account;
    }

    public function toData()
    {
        $data = new stdClass();
        $this->dataTrackCreate($data);
        $this->dataHasGeneratedID($data);
        return $data;
    }

  
}
