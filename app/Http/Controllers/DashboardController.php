<?php

namespace App\Http\Controllers;

use App\Helpers\DashboardHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\PeriodHelper;

class DashboardController extends Controller
{
    //
    public function dashboard()
    {

        $period = PeriodHelper::currentPeriod();
        if (!$period) {
            return redirect()->route('period-management');
        }

        $userType = DashboardHelper::currentView();

        return view(($userType) . '/dashboard');
    }
}
