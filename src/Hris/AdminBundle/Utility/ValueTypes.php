<?php

namespace Hris\AdminBundle\Utility;

use ReflectionClass;

class ValueTypes
{
    const AMOUNT = '1';
    const PERCENT = '2';

    public static function isValidStatus($status)
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\ValueTypes');
        $values = array_values($reflection->getConstants());
        return in_array($status, $values);
    }

    public static function getValueTypesOptions()
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\ValueTypes');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

