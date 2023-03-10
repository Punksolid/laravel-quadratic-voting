<?php

namespace LaravelQuadraticVoting\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

interface IsVotableInterface
{
    public function voters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany;

    public function getCountVotes(): int;

    public function getVoters(): \Illuminate\Support\Collection;

}
