<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AssessmentProvider extends ServiceProvider
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
        $this->app->booted(function() {
            $schedule = $this->app->make(Schedule::class);
            $schedule->command('assessment:generate')->daily();
        });
    }
}
