<?php

namespace Gist\InventoryBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gist\CoreBundle\Template\Entity\HasGeneratedID;
use Gist\CoreBundle\Template\Entity\HasName;
use stdClass;

/**
 * @ORM\Entity
 * @ORM\Table(name="inv_product_type")
 */
class ProductType
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