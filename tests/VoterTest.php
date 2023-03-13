<?php

namespace LaravelQuadraticVoting\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelQuadraticVoting\Models\User;

class VoterTest extends TestCase
{
    use RefreshDatabase;
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../tests/database/migrations');
        $this->loadMigrationsFrom(__DIR__ . '/../vendor/orchestra/testbench-core/laravel/migrations');
    }

    /** @test */
    public function test_reset_vote_credits()
    {
        $users = User::factory(10)->create();

        User::massiveVoteCredits($users, 10);
        User::massiveVoteReset($users);
        $credits = $users->first()->getVoteCredits();

        $this->assertEquals(0, $credits);
    }
}
