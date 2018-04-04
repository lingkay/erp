<?php

namespace Utility;

use ReflectionClass;

class SalaryTypes
{
    const PER_HOUR = '1';
    const PER_DAY = '2';
    const PER_MONTH = '3';
    const PER_PROJECT = '4';

    public static function getOptions()
    {
        $reflection = new ReflectionClass('Utility\SalaryTypes');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $display = str_replace('_', ' ', $id);
            $display = strtolower($display);
            $display = ucwords($display);
            $opts[$value] = $display;
        }

        return $opts;
    }
}
