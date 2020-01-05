<?php

namespace Punksolid\LaravelQuadraticVoting\Tests;

use App\Article;
use App\Idea;
use App\User;
use App\Vote;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VotingTest extends TestCase
{
    public function test_user_can_vote_an_idea()
    {
        $user = factory(User::class)->create();
        $user->giveVoteCredits(5);
        $idea = factory(Idea::class)->create();

        $this->assertTrue($user->voteOn($idea, 5));
    }

    public function test_get_votes_of_a_thing()
    {
        $user = factory(User::class)->create();
        $user->giveVoteCredits(16);
        $idea = factory(Idea::class)->create();
        $user->voteOn($idea, 12);
        $user->voteOn($idea, 4);

        $this->assertEquals(4, $idea->getCountVotes());
    }

    public function test_get_votes_default()
    {
        $idea = factory(Idea::class)->create();

        $this->assertEquals(0, $idea->getCountVotes());

    }

    public function test_get_voters()
    {
        $user = factory(User::class)->create();
        $user->giveVoteCredits(4);
        $idea = factory(Idea::class)->create();

        $user->voteOn($idea, 1);
        $user->voteOn($idea, 3);

        $this->assertEquals(1, $idea->getVoters()->count());
        $this->assertEquals($user->name, $idea->getVoters()->first()->name);
    }

    public function test_give_vote_credits()
    {
        $user = factory(User::class)->create();

        $user->giveVoteCredits(10);
        $user->giveVoteCredits(9);

        $this->assertEquals(19, $user->getVoteCredits());
    }

    public function test_vote_cost_exponentially()
    {
        $user = factory(User::class)->create();
        $user->giveVoteCredits(14);
        $idea = factory(Idea::class)->create();

        $this->assertTrue($user->voteOn($idea, 14));
        $this->assertEquals(0, $user->getVoteCredits(), "Cant spend correctly");
        $this->assertEquals(4, $idea->getCountVotes());

    }

    public function test_cant_vote_without_credits()
    {
        $user = factory(User::class)->create();
        $idea = factory(Idea::class)->create();

        $this->assertFalse($user->voteOn($idea, 10));

    }

    public function test_cant_vote_more_than_his_credits()
    {
        $user = factory(User::class)->create();
        $idea = factory(Idea::class)->create();
        $user->giveVoteCredits(5);

        $this->assertFalse($user->voteOn($idea, 10));
    }

    public function test_two_voters_can_vote_same_idea()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $user1->giveVoteCredits(9);
        $user2->giveVoteCredits(9);
        $idea = factory(Idea::class)->create();

        $user1->voteOn($idea, 9);
        $user2->voteOn($idea, 9);

        $this->assertEquals(6, $idea->getCountVotes());

    }

    public function test_vote_other_elements()
    {
        $user = factory(User::class)->create();
        $user->giveVoteCredits(1);
        $article = factory(Article::class)->create();

        $user->voteOn($article);

        $this->assertEquals(1, $article->getCountVotes());
    }

    public function test_give_vote_credits_massively()
    {
        $users = factory(User::class, 10)->create();

        User::massiveVoteCredits($users, 10);

        $credits = $users->first()->getVoteCredits();

        $this->assertEquals(10, $credits);
    }
}
