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
use Punksolid\LaravelQuadraticVoting\Interfaces\VoterInterface;

trait isVotable
{
    public function voters(): BelongsToMany
    {
        return $this
            ->belongsToMany(
                VoterInterface::class,
                "votes",
                'votable_id',
                config('laravel_quadratic.column_names.voter_key')
            )->withPivot([
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
            ->groupBy(
                config('laravel_quadratic.column_names.voter_key')
            )
            ->get();
    }
}
