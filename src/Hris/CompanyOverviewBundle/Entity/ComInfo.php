<?php

namespace Hris\CompanyOverviewBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\ContactBundle\Template\Entity\HasPhones;
use Catalyst\CoreBundle\Template\Entity\TrackCreate;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="comp_profile")
 */
class ComInfo
{
    use HasGeneratedID;
    use HasPhones;
    use TrackCreate;

    public function __construct()
    {
        $this->initTrackCreate();
        $this->initHasPhones();
    }

    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasPhones($data);
        return $data;
    }
}