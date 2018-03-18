<?php

namespace Gist\POSERPBundle;
use ReflectionClass;

class ModesOfPayment
{
    const CASH = 'Cash';
    const CREDIT_CARD = 'Credit Card';
    const CHECK = 'Check';
    const GC = 'Gift Card';

    public static function isValidStatus($status)
    {
        $reflection = new ReflectionClass('Gist\POSERPBundle\ModesOfPayment');
        $values = array_values($reflection->getConstants());
        return in_array($status, $values);
    }

    public static function getModesOptions()
    {
        $reflection = new ReflectionClass('Gist\POSERPBundle\ModesOfPayment');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

