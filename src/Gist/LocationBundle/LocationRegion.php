<?php

namespace Gist\LocationBundle;
use ReflectionClass;

class LocationRegion
{
    const R1 = 'REGION I (ILOCOS REGION)';
    const R2 = 'REGION II (CAGAYAN VALLEY)';
    const R3 = 'REGION III (CENTRAL LUZON)';
    const R4A = 'REGION IV-A (CALABARZON)';
    const R4B = 'REGION IV-B (MIMAROPA)';
    const R5 = 'REGION V (BICOL REGION)';
    const R6 = 'REGION VI (WESTERN VISAYAS)';
    const R7 = 'REGION VII (CENTRAL VISAYAS)';
    const R8 = 'REGION VIII (EASTERN VISAYAS)';
    const R9 = 'REGION IX (ZAMBOANGA PENINSULA)';
    const R10 = 'REGION X (NORTHERN MINDANAO)';
    const R11 = 'REGION XI (DAVAO REGION)';
    const R12 = 'REGION XII (SOCCSKSARGEN)';
    const NCR ='NATIONAL CAPITAL REGION (NCR)';
    const CAR = 'CORDILLERA ADMINISTRATIVE REGION (CAR)';
    const ARMM = 'AUTONOMOUS REGION IN MUSLIM MINDANAO (ARMM)';
    const R13 = 'REGION XIII (Caraga)';

    public static function isValidStatus($status)
    {
        $reflection = new ReflectionClass('Gist\LocationBundle\LocationRegion');
        $values = array_values($reflection->getConstants());
        return in_array($status, $values);
    }

    public static function getRegionOptions()
    {
        $reflection = new ReflectionClass('Gist\LocationBundle\LocationRegion');
        $values = $reflection->getConstants();
        $opts = array();
        foreach ($values as $id => $value) {
            $opts[$id] = $value;
        }

        return $opts;
    }
}

