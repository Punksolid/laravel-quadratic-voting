<?php

namespace Punksolid\LaravelQuadraticVoting;

use Illuminate\Support\ServiceProvider;
use Punksolid\LaravelQuadraticVoting\Interfaces\IsVotableInterface;
use Punksolid\LaravelQuadraticVoting\Interfaces\VoterInterface;

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

        $this->publishes([
            $this->getBaseDir('config') => config_path(),
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
            $this->getBaseDir('config/permission.php'),
            'permission'
        );
    }

    private function registerModelBindings()
    {
        $config = $this->app['config']['permission.models'];

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
