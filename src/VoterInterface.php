<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 26/10/18
 * Time: 01:29 AM
 */

namespace Punksolid\LaravelQuadraticVoting;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

interface VoterInterface
{
    /**
     * @param VoterInterface $model
     * @param int $vote_credits
     *
     * @return bool
     */
    public function voteOn(VoterInterface $model, $vote_credits): bool;

    /**
     * @param int $wanna_spend
     *
     * @return bool
     */
    public function hasCredits($wanna_spend): bool;

    /**
     * @param int $credits
     *
     * @return int
     */
    public function spendCredits($credits): int;

    /**
     * @param $vote_credits
     *
     * @return VoteCredit
     */
    public function giveVoteCredits($vote_credits);

    public function ideas(): BelongsToMany;
    public function voteCredits(): HasOne;
    public function getVoteCredits(): int;
}
