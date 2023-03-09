<?php

namespace LaravelQuadraticVoting\Tests;

class TestCase extends \Orchestra\Testbench\TestCase
{

    protected function getEnvironmentSetUp($app)
    {
        // Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
        $app['config']->set('laravel_quadratic.column_names.voter_key', 'voter_id');
        $app['config']->set('laravel_quadratic.table_names.votes', 'votes');
        $app['config']->set('laravel_quadratic.table_names.vote_credits', 'vote_bag');
    }
}