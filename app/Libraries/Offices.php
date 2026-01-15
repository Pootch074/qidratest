<?php

namespace App\Libraries;

use App\Models\Office;

class Offices
{
    protected static $offices = null;
    public static function all()
    {
        if (self::$offices === null) {
            self::$offices = Office::pluck('office_name', 'id')->toArray();
        }

        return self::$offices;
    }
}