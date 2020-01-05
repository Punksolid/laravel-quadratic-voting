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
        $this->loadMigrationsFrom($this->getBaseDir('database/migrations'));
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * @param string $directory
     *
     * @return string
     */
    private function getBaseDir($directory): string
    {
        return sprintf(
            '%s/../%s',
            __DIR__,
            $directory
        );
    }
}
