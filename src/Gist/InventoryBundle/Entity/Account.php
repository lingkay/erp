<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\TrackCreate;
use Gist\CoreBundle\Template\Entity\HasName;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_account")
 */
class Account
{
    use HasGeneratedID;
    use TrackCreate;
    use HasName;

    /** @ORM\Column(type="boolean") */
    protected $allow_negative;

    public function __construct()
    {
        $this->initHasGeneratedID();
        $this->initTrackCreate();
        $this->initHasName();

        $this->allow_negative = false;
    }

    public function setAllowNegative($allow = true)
    {
        $this->allow_negative = $allow;
        return $this;
    }

    public function canAllowNegative()
    {
        if ($this->allow_negative)
            return true;

        return false;
    }

    public function toData()
    {
        $data = new \stdClass();

        $this->dataHasGeneratedID($data);
        $this->dataTrackCreate($data);
        $this->dataHasName($data);

        return $data;
    }
}
