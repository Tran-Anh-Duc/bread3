<?php

namespace Modules\ReportsKsnb\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{

    public function boot()
    {
        $this->commands([
            //
        ]);
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);
            
        });
    }

    public function register()
    {
    }
}