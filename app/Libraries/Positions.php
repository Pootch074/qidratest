<?php

namespace App\Libraries;

use App\Models\Position;

class Positions
{
    protected static $positions = null;

    public static function all(): array
    {
        if (self::$positions === null) {
            self::$positions = Position::pluck('position_name', 'id')->toArray();
        }

        return self::$positions;
    }
}
