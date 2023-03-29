<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/10/18
 * Time: 01:29 AM
 */

namespace LaravelQuadraticVoting\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use LaravelQuadraticVoting\Models\VoteCredit;

interface VoterInterface
{
    public function voteOn(Model $model, int $vote_credits = 1): int;

    public function hasCredits(int $wanna_spend): bool;

    public function spendCredits(int $credits): int;

    public function ideas(): BelongsToMany;

    public function voteCredits(): HasOne;

    public function giveVoteCredits(int $vote_credits = 1): VoteCredit;

    public function getVoteCredits(): int;

    public static function massiveVoteCredits(Collection $voters, int $credits): Collection;
}
