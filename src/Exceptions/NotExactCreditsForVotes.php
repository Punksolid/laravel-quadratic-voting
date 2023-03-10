<?php

namespace LaravelQuadraticVoting\Exceptions;

class NotExactCreditsForVotes extends \Exception
{
    public function __construct($message = 'The credits for votes must be exact for the votes wanted')
    {
        parent::__construct($message);
    }
}
