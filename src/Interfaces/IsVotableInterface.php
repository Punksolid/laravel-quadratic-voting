<?php

namespace Punksolid\LaravelQuadraticVoting\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

interface IsVotableInterface
{
    public function voters(): BelongsToMany;

    public function getCountVotes(): bool;

    public function getVoters(): Collection;

}
