<?php

namespace LaravelQuadraticVoting\Tests;

use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelQuadraticVoting\Models\Idea;
use LaravelQuadraticVoting\Models\User;

class VotingTest extends TestCase
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


    /**
     * @return void
     * @throws Exception
     * @covers \LaravelQuadraticVoting\Traits\VoterTrait::voteOn
     */
    public function test_user_can_vote_an_idea(): void
    {

        // create a user mock object
        /** @var User $user */
        $user = User::factory()->create();


        $user->giveVoteCredits(5);
        $idea = Idea::factory()->create();

        $this->assertEquals(1, $user->voteOn($idea, 1));
    }

    /**
     * @return void
     */
    public function test_get_votes_of_a_thing_as_paying_in_consecutive_cost(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Idea $idea */
        $idea = Idea::factory()->create();

        $user->giveVoteCredits(100);
        $user->voteOn($idea, 1);
        $this->assertEquals(1, $idea->getCountVotes());
        $user->voteOn($idea, 4);
        $this->assertEquals(2, $idea->getCountVotes());
        $user->voteOn($idea, 9);
        $this->assertEquals(3, $idea->getCountVotes());
        $user->voteOn($idea, 16);
        $this->assertEquals(4, $idea->getCountVotes());
        $user->voteOn($idea, 25);
        $this->assertEquals(5, $idea->getCountVotes());
        $user->voteOn($idea, 36);
        $this->assertEquals(6, $idea->getCountVotes());
    }

    /**
     * Assert throw exception when user has not enough credits
     * @return void
     * @throws Exception
     * @covers \LaravelQuadraticVoting\Traits\VoterTrait::voteOn
     */
    public function test_get_votes_of_a_thing_not_paying_in_order(): void
    {

//        $this->expectException(\Exception::class);
        /** @var User $user */
        $user = User::factory()->create();
        /** @var Idea $idea */
        $idea = Idea::factory()->create();

        $user->giveVoteCredits(100);
        $user->voteOn($idea, 14);

        $this->assertEquals(3, $idea->getCountVotes());

    }

    public function test_get_votes_default()
    {
        $idea = Idea::factory()->create();

        $this->assertEquals(0, $idea->getCountVotes());

    }

    public function test_vote_costs_exponentially()
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->giveVoteCredits(14);
        $idea = Idea::factory()->create();

        $this->assertEquals(3, $user->voteOn($idea, 14));
        $this->assertEquals(0, $user->getVoteCredits(), "Cant spend correctly");
        $this->assertEquals(3, $idea->getCountVotes());

    }

    public function test_cant_vote_without_credits()
    {
        $this->expectException(Exception::class);

        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $user->voteOn($idea, 14);
    }

    public function test_cant_vote_more_than_his_credits()
    {
        $this->expectException(Exception::class);

        /** @var User $user */
        $user = User::factory()->create();
        $idea = Idea::factory()->create();

        $user->giveVoteCredits(5);

        $user->voteOn($idea, 14);

    }

    /** @test */
    public function test_two_voters_can_vote_same_idea()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user1->giveVoteCredits(100);
        $user2->giveVoteCredits(100);
        $idea = Idea::factory()->create();

        $user1->voteOn($idea, 14);
        $user2->voteOn($idea, 14);

        $this->assertEquals(6, $idea->getCountVotes());

    }

    public function test_give_vote_credits_massively()
    {
        $users = User::factory(10)->create();

        User::massiveVoteCredits($users, 10);

        $credits = $users->first()->getVoteCredits();

        $this->assertEquals(10, $credits);
    }
}
