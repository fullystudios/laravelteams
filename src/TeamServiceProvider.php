<?php

namespace FullyStudios\LaravelTeams;

use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadRoutesFrom(__DIR__.'/teamroutes.php');
        $this->loadMigrationsFrom(__DIR__.'/Migrations');
        $this->loadViewsFrom(__DIR__.'/Views', 'laravelteams');
        $this->publishes([
           __DIR__.'/Views' => resource_path('views/vendor/laravelteams'),
       ]);
        $this->publishes([
           __DIR__.'/Migrations' => database_path('migrations'),
       ]);


    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
