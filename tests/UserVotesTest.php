<?php

namespace LaravelQuadraticVoting\Tests;

use Exception;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use LaravelQuadraticVoting\Models\Idea;
use LaravelQuadraticVoting\Models\User;

class UserVotesTest extends TestCase
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
    public function test_user_can_get_next_vote_cost(): void
    {

        // create a user mock object
        /** @var User $user */
        $user = User::factory()->create();

        $user->giveVoteCredits(100);
        $idea = Idea::factory()->create();
        $user->voteOn($idea, 1);
        $this->assertEquals(4, $user->getNextVoteCost());
        $user->voteOn($idea, 4);
        $this->assertEquals(9, $user->getNextVoteCost());
    }

    /** @test  */
    public function it_can_vote_two_ideas(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->giveVoteCredits(100);
        /** @var Idea $idea1 */
        $idea1 = Idea::factory()->create();
        $idea2 = Idea::factory()->create();

        $user->voteOn($idea1, 5);
        $this->assertEquals(2, $idea1->getCountVotes());
        $user->voteOn($idea2, 5);
        $this->assertEquals(2, $idea1->getCountVotes());
    }

    /** @test  */
    public function two_users_can_vote_one_idea(): void
    {

        /** @var User $user */
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user1->giveVoteCredits(100);
        $user2->giveVoteCredits(100);
        /** @var Idea $idea */
        $idea = Idea::factory()->create();

        $user1->voteOn($idea, 14);
        $user2->voteOn($idea, 14);

        $this->assertEquals(6, $idea->getCountVotes());
    }

    public function test_two_users_can_vote_two_ideas()
    {
        $user1 = tap(User::factory()->create())->giveVoteCredits(100);
        $user2 = tap(User::factory()->create())->giveVoteCredits(100);
        $idea1 = Idea::factory()->create();
        $idea2 = Idea::factory()->create();
        $user1->voteOn($idea1, 14);
        $user2->voteOn($idea2, 14);

        $this->assertEquals(3, $idea1->getCountVotes());
        $this->assertEquals(3, $idea1->getCountVotes());

    }

    public function test_get_voters_of_an_idea(): void
    {
        /** @var User $user1 */
        $user1 = User::factory()->create();
        /** @var User $user2 */
        $user2 = User::factory()->create();
        $user1->giveVoteCredits(100);
        $user2->giveVoteCredits(100);

        /** @var Idea $idea */
        $idea = Idea::factory()->create();

        $user1->voteOn($idea, 1);
        $user2->voteOn($idea, 14);

        $this->assertEquals(2, $idea->getVoters()->count());
        $this->assertEquals($user1->name, $idea->getVoters()->first()->name);
    }

    public function test_give_vote_credits()
    {
        $user = User::factory()->create();

        $user->giveVoteCredits(10);
        $user->giveVoteCredits(9);

        $this->assertEquals(19, $user->getVoteCredits());
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
