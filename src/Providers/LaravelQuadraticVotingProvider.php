<?php

namespace LaravelQuadraticVoting\Providers;

use Illuminate\Support\ServiceProvider;
use LaravelQuadraticVoting\Interfaces\IsVotableInterface;
use LaravelQuadraticVoting\Interfaces\VoterInterface;

class LaravelQuadraticVotingProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->publishes([
            __DIR__ . '/../config/laravel_quadratic.php' => config_path('laravel_quadratic.php')
            ], 'config');

        $this->publishes(
            [
                $this->getBaseDir('database/migrations') => database_path('migrations'),
            ],
            'laravel-quadratic-migrations'
        );

        $this->registerModelBindings();


    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../../config/laravel_quadratic.php',
            'laravel_quadratic'
        );
    }

    private function registerModelBindings()
    {
        $config = $this->app['config']['laravel_quadratic.models'];

        $this->app->bind(VoterInterface::class, $config['voter']);
        $this->app->bind(IsVotableInterface::class, $config['vote_credit']);
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
