<?php

namespace LaravelQuadraticVoting\Models;


use Database\Factories\IdeaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use LaravelQuadraticVoting\Interfaces\IsVotableInterface;
use LaravelQuadraticVoting\Traits\IsVotable;

/**
 * This is a dummy model to test the package
 */
class Idea extends Model implements IsVotableInterface
{
    use IsVotable;
    use HasFactory;

    protected static function newFactory()
    {
        return IdeaFactory::new();
    }
}
