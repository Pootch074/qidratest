<?php

namespace App\Helpers;

use Illuminate\Support\Str;

class DashboardHelper
{
    public static function currentView()
    {
        $userType = Str::lower(auth()->user()->getUserTypeName());
        if ($userType == 'team leader') $userType = 'rmt';

        return $userType;
    }
}
