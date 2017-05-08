<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BOMOutput extends BOMAsset
{
    /**
     * @ORM\ManyToOne(targetEntity="BillOfMaterial", inversedBy="outputs")
     * @ORM\JoinColumn(name="bom_id", referencedColumnName="id")
     */
    protected $bom;
}
