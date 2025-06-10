<?php

namespace App\Providers;

use App\Models\Period;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        View::composer('layouts.inc.topnav', function($view) {
            $currentPeriod = Period::where('status', 'ongoing')->first();
            $view->with('currentPeriod', $currentPeriod);
        });
    }
}
