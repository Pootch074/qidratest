<?php

namespace App\Helpers;

use App\Models\Period;

class PeriodHelper
{
    public static function currentPeriod()
    {
        return Period::where('status', 'ongoing')->first();
    }

    public static function currentPeriodId()
    {
        return optional(self::currentPeriod())->id;
    }
}
