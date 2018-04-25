<?php

namespace Hris\AdminBundle\Utility;

use ReflectionClass;

class FineTypes
{
    const AUTOMATIC = '1';
    const MANUAL = '2';

    public static function getIncentivePeriodOptions()
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\FineTypes');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

