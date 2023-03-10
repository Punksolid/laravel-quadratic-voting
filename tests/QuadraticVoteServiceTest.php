<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/10/18
 * Time: 01:21 AM
 */

namespace LaravelQuadraticVoting\Tests;

use LaravelQuadraticVoting\Exceptions\NotExactCreditsForVotes;
use LaravelQuadraticVoting\Services\QuadraticVoteService;
use Orchestra\Testbench\TestCase;

class QuadraticVoteServiceTest extends TestCase
{
    /** @test */
    public function it_gives_1_vote_for_1_credit()
    {
        $quadratic_vote_service = new QuadraticVoteService();

        $this->assertEquals(1, $quadratic_vote_service->processCreditsToVotes(1));
    }

    /** @test */
    public function it_throws_error_when_credits_are_not_exact_for_integer_votes()
    {
        $quadratic_vote_service = new QuadraticVoteService();

        $this->expectException(NotExactCreditsForVotes::class);
        $quadratic_vote_service->processCreditsToVotes(2);
    }

    /** @test */
    public function it_gives_3_votes_for_14_credits()
    {
        $quadratic_vote_service = new QuadraticVoteService();

        $this->assertEquals(3, $quadratic_vote_service->processCreditsToVotes(14));
    }

    /** @test */
    public function it_does_not_accept_negative_values()
    {
        $quadratic_vote_service = new QuadraticVoteService();

        $this->expectException(NotExactCreditsForVotes::class);
        $quadratic_vote_service->processCreditsToVotes(-1);
    }
}
