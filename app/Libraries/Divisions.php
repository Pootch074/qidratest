<?php

namespace App\Libraries;

use App\Models\Division;

class Divisions
{
    protected static $divisions = null;
    public static function all()
    {
        if (self::$divisions === null) {
            self::$divisions = Division::pluck('division_name', 'id')->toArray();
        }

        return self::$divisions;
    }
}