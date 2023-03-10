<?php

namespace LaravelQuadraticVoting\Models;


use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelQuadraticVoting\Interfaces\VoterInterface;
use LaravelQuadraticVoting\Traits\VoterTrait;

/**
 * Dummy model
 */
class User extends Model implements VoterInterface
{

    use VoterTrait;
    use HasFactory;


    // specify the factory class path
    protected static function newFactory()
    {
        return UserFactory::new();
    }

}
