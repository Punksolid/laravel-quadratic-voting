<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/10/18
 * Time: 01:29 AM
 */

namespace Punksolid\LaravelQuadraticVoting\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

interface VoterInterface
{
    public function voteOn(Model $model, int $vote_credits = 1): bool;

    public function hasCredits(int $wanna_spend): bool;

    public function spendCredits(int $credits): int;

    public function ideas(): BelongsToMany;

    public function voteCredits(): HasOne;

    public function giveVoteCredits(int $vote_credits = 1): VoteCredit;

    public function getVoteCredits(): int;

    static function massiveVoteCredits(Collection $voters, int $credits): Collection;

}
