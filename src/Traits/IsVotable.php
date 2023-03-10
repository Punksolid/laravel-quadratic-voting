<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 27/10/18
 * Time: 12:04 AM
 */

namespace LaravelQuadraticVoting\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

trait IsVotable
{
    public function voters(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this
            ->belongsToMany(
                \config('laravel_quadratic.models.voter'),
                "votes",
                'votable_id',
                \config('laravel_quadratic.column_names.voter_key')
            )->withPivot([
                "votable_type",
                "votable_id",
                "quantity"
            ]);
    }

    public function getCountVotes(): int
    {
        return $this->voters()->sum("quantity");
    }

    public function getVoters(): Collection
    {
        return $this
            ->voters()
            ->groupBy(
                \config('laravel_quadratic.column_names.voter_key')
            )
            ->get();
    }
}
