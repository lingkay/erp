<?php

namespace Gist\ManufacturingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class BOMInput extends BOMAsset
{
    /**
     * @ORM\ManyToOne(targetEntity="BillOfMaterial", inversedBy="inputs")
     * @ORM\JoinColumn(name="bom_id", referencedColumnName="id")
     */
    protected $bom;
}
