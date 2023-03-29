<?php

namespace LaravelQuadraticVoting\Tests;

use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelQuadraticVoting\Models\Politician;
use LaravelQuadraticVoting\Models\User;

class IsVotableTest extends TestCase
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

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);
        $app['config']->set('laravel_quadratic.models.is_votable', Politician::class);
    }

    /**
     * @return void
     * @throws Exception
     * @covers \LaravelQuadraticVoting\Traits\VoterTrait::voteOn
     */
    public function test_user_can_vote_another_entity(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $user->giveVoteCredits(100);
        $politician = Politician::factory()->create();
        $user->voteOn($politician, 1);
        $this->assertEquals(4, $user->getNextVoteCost($politician));
        $user->voteOn($politician, 4);
        $this->assertEquals(9, $user->getNextVoteCost($politician));
    }
}
