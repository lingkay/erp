<?php

namespace Hris\AdminBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;

use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="world_location")
 */
class WorldLocation
{
    use HasGeneratedID;
    use HasName;


    /** @ORM\Column(name="location_type", type="string", length=80) */
    protected $type;

    /** @ORM\Column(name="parent_id", type="integer", length=80) */
    protected $parent;

    /** @ORM\Column(name="is_visible", type="integer", length=80) */
    protected $visibility;

    public function __construct()
    {

    }

    public function getName()
    {
        return $this->name;
    }

    public function getParent()
    {
        return $this->parent;
    }

    public function getType()
    {
        return $this->type;
    }





    public function toData()
    {
        $data = new \stdClass();
        $this->dataHasGeneratedID($data);
        $this->dataHasName($data);

        return $data;
    }
}
