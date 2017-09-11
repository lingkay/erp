<?php

namespace Gist\GridBundle\Model\Grid;

class Event
{
    // 100 series for query builder
    const QB_BUILD          = 101;

    // 200 series for row turnover
    const ROW_BUILD         = 201;


    public static function isValid($event_id)
    {
        switch($event_id)
        {
            case self::QB_BUILD:
            case self::ROW_BUILD:
                return true;
        }

        return false;
    }
}
