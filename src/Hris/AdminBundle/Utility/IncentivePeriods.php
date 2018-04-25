<?php

namespace Hris\AdminBundle\Utility;

use ReflectionClass;

class IncentivePeriods
{
    const DAY = '1';
    const WEEK = '2';
    const MONTH = '3';
    const QUARTER = '4';
    const SEMI_ANNUAL = '5';
    const ANNUAL = '6';

    public static function getIncentivePeriodOptions()
    {
        $reflection = new ReflectionClass('Hris\AdminBundle\Utility\IncentivePeriods');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

