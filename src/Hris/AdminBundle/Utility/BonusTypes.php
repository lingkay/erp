<?php

namespace Hris\AdminBundle\Utility;

use ReflectionClass;

class BonusTypes
{
    const FIXED = '1';
    const ADJUSTABLE = '2';

    public static function isValidStatus($status)
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\BonusTypes');
        $values = array_values($reflection->getConstants());
        return in_array($status, $values);
    }

    public static function getBonusTypesOptions()
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\BonusTypes');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

