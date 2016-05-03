<?php

namespace Catalyst\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Catalyst\CoreBundle\Template\Entity\HasGeneratedID;
use Catalyst\CoreBundle\Template\Entity\HasName;

/** 
* @ORM\Entity
* @ORM\Table(name="cnt_type")
*/
class ContactType
{
    use HasGeneratedID;
    use HasName;

    public function __construct()
    {
            $this->initHasGeneratedID();
    }

    public function toData()
    {
            $data = new \stdClass();
            $this->dataHasGeneratedID($data);
            $this->dataHasName($data);
            return $data;
    }
}