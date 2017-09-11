<?php

namespace Gist\CoreBundle\Template\Entity;
use Gist\ValidationException;
use Doctrine\ORM\Mapping as ORM;

trait HasQuantity
{
    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $quantity;


    public function setQuantity($quantity)
    {
        if (is_numeric($quantity))
        {
            $this->quantity = $quantity;
        }
        elseif ($quantity == NULL)
        {
            $this->quantity = 0;
        }
        else 
        {
            throw new ValidationException('Invalid value for quantity');
        }

        return $this;
    }

    public function getQuantity()
    {
        return $this->quantity;
    }
    
    public function initHasQuantity()
    {
        $this->quantity = 0.00;
    }


    public function dataHasQuantity($data)
    {
        $data->quantity = $this->quantity;
    }
}
