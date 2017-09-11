<?php

namespace Gist\CoreBundle\Template\Entity;
use Gist\ValidationException;
use Doctrine\ORM\Mapping as ORM;

trait HasPrice
{
    /** @ORM\Column(type="decimal", precision=10, scale=2) */
    protected $price;

    public function setPrice($price)
    {
        if(is_numeric($price)){
            $this->price = $price;
        }else {
            throw new ValidationException('Invalid value for price');
        }
        return $this;
    }

    public function getPrice()
    {
        return $this->price;
    }


    public function initHasPrice()
    {
        $this->price = 0.00;
    }
    
    public function dataHasPrice($data)
    {
        $data->price = $this->price;
    }
}
