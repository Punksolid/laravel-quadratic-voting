<?php

namespace Punksolid\LaravelQuadraticVoting\Tests;

use App\User;
use Orchestra\Testbench\TestCase;
use Punksolid\LaravelQuadraticVoting\VoterInterface;

/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/10/18
 * Time: 01:21 AM
 */


class QuadraticVotingTest extends TestCase
{
    public function test_user_is_voter()
    {
        $user = factory(User::class)->make();
        $this->assertTrue(
            $user instanceof VoterInterface
        );
    }
}
