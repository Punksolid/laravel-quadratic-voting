<?php

namespace Punksolid\LaravelQuadraticVoting;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

interface VotableInterface
{
    public function voters(): BelongsToMany;
    public function getCountVotes(): bool;
    public function getVoters(): Collection;
}
