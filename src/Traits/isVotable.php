<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 27/10/18
 * Time: 12:04 AM
 */

namespace Punksolid\LaravelQuadraticVoting\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Punksolid\LaravelQuadraticVoting\VoterInterface;

trait isVotable
{

    public function voters(): BelongsToMany
    {
        return $this->belongsToMany(
            VoterInterface::class,
            "votes",
            'votable_id',
            'voter_id')
            ->withPivot([
                "votable_type",
                "votable_id",
                "quantity"
            ]);
    }

    public function getCountVotes(): bool
    {
        return $this->voters()->sum("quantity");
    }

    public function getVoters(): Collection
    {
        return $this
            ->voters()
            ->groupBy('voter_id')
            ->get();
    }
}