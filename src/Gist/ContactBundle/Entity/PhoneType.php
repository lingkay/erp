<?php

namespace Gist\ContactBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

/**
* @ORM\Entity
* @ORM\Table(name="cnt_phone_type")
*/
class PhoneType
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