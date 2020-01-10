<?php

namespace Punksolid\LaravelQuadraticVoting;

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
        $this->loadMigrationsFrom(
            $this->getBaseDir('database/migrations')
        );

        $this->publishes(
            [
                $this->getBaseDir('database/migrations') => database_path('migrations'),
            ],
            'laravel-quadratic-migrations'
        );
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    protected function getBaseDir(string $path): string
    {
        return sprintf(
            '%s/../%s',
            __DIR__,
            $path
        );
    }
}
