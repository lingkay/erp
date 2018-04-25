<?php

namespace Hris\AdminBundle\Utility;

use ReflectionClass;

class FineValueTypes
{
    const FORMULA = '1';
    const FIXED = '2';

    public static function getFinesValueTypesOptions()
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\FineValueTypes');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

