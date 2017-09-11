<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\HasCode;
use Gist\UserBundle\Entity\User;
use DateTime;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_issued_item")
 */
class IssuedItem
{
	use HasGeneratedID;
	use Hascode;
	use TrackCreate;

    /** @ORM\Column(type="date") */
    protected $date_issue;

    /** 
     * @ORM\ManyToOne(targetEntity="\Gist\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="user_issuedto_id", referencedColumnName="id")
     */
    protected $issued_to;

    /**
     * @ORM\OneToMany(targetEntity="IIEntry", mappedBy="issued", cascade={"persist"})
     */
    protected $entries;

	public function __construct()
	{
		$this->initHasGeneratedID();
        $this->initTrackCreate();
		$this->entries = new ArrayCollection();
	}

    public function setIssuedTo(User $user)
    {
        $this->issued_to = $user;
        return $this;
    }

    public function setDateIssue(DateTime $date)
    {
        $this->date_issue = $date;
        return $this;
    }

    public function addEntry(IIEntry $entry)
    {
        $this->entries->add($entry);
        $entry->setIssued($this);
        return $this;
    }

    public function clearEntries()
    {
        $this->entries->clear();
        return $this;
    }
    
    public function getEntries()
    {
        return $this->entries;
    }

    public function getTotalItem()
    {
        return count($this->getEntries());
    }

    public function getDateIssue()
    {
        return $this->date_issue;
    }

    public function getIssuedTo()
    {
        return $this->issued_to;
    }

    public function getDateIssueFormat()
    {
        return $this->date_issue->format('m/d/Y');
    }

    public function generateCode()
    {        
        $this->code = str_pad($this->id,5, "0", STR_PAD_LEFT);
    }


	public function toData()
	{
		$data = new \stdClass();

		$this->dataHasGeneratedID($data);
		$this->dataTrackCreate($data);
		$this->dataHasCode($data);
		$data->issued_to = $this->issued_to;
		$data->date_issue = $this->date_issue;

		$entries = array();
        foreach ($this->entries as $entry)
            $entries[] = $entry->toData();
        $data->entries = $entries;		

		return $data;
	}
}