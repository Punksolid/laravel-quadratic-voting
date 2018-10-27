<?php

namespace Punksolid\LaravelQuadraticVoting;
//namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LaravelQuadraticVotingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/migrations/');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }
}
