<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 27/10/18
 * Time: 12:03 AM
 */

namespace Punksolid\LaravelQuadraticVoting;


use App\Idea;
use Punksolid\LaravelQuadraticVoting\VoterInterface;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Relations\HasOne;

trait Voter
{

    /**
     * @param VoterInterface $model
     * @param int $vote_credits
     *
     * @return bool
     */
    public function voteOn(VoterInterface $model, $vote_credits = 1): bool
    {
        if (!$this->hasCredits($vote_credits)) {
            return false;
        }

        $votes_already_emitted = $this->ideas()->groupBy('voter_id')->get()->sum('credits');
        $this->ideas()->detach();
        $votes_credits_already_emitted = pow($votes_already_emitted, 2);
        $total_vote_credits = $vote_credits + $votes_credits_already_emitted;
        $votes_quantity = sqrt($total_vote_credits); //new

        $this->ideas()->attach($model->id, [
            "voter_id" => $this->id,
            "votable_type" => get_class($model),
            "votable_id" => $model->id,
            "quantity" => $votes_quantity
        ]);

        $this->spendCredits($vote_credits);

        return true;

    }

    /**
     * @param int $wanna_spend
     *
     * @return bool
     */
    public function hasCredits($wanna_spend): bool
    {
        $vote_bag = $this->voteCredits()->first();
        return $vote_bag && $vote_bag->credits >= $wanna_spend;
    }

    /**
     * @param int $credits
     *
     * @return int
     */
    public function spendCredits($credits): int
    {
        $voter_bag = $this->voteCredits()->first();
        if ($voter_bag) {
            $voter_bag->update([
                'credits' => $voter_bag->credits -= $credits
            ]);
            return $voter_bag->credits;
        }

        return 0;
    }

    public function ideas(): BelongsToMany
    {
        return $this->belongsToMany(Idea::class, "votes", "voter_id", "votable_id")
            ->withPivot([
                "votable_type",
                "votable_id",
                "quantity"
            ])->withTimestamps();
    }

    public function voteCredits(): HasOne
    {
        return $this->hasOne(VoteCredit::class, 'voter_id');
    }

    /**
     * Sums vote credits
     * @param int $vote_credits
     *
     * @return VoteCredit
     */
    public function giveVoteCredits($vote_credits = 1)
    {
        /** @var VoteCredit $vote_bag */
        $vote_bag = $this->voteCredits()->first();

        if ($vote_bag) { // update
            $vote_bag->update(['credits' => $vote_bag->credits + $vote_credits]);
        } else { //save
            $vote_bag = $this->voteCredits()->create(['credits' => $vote_credits]);
        }

        return $vote_bag;
    }

    public function getVoteCredits(): int
    {
        return (int)optional($this->voteCredits()->first())->credits;
    }

    /**
     * @param Collection $voters
     * @param int $credits
     *
     * @return Collection
     */
    static function massiveVoteCredits(Collection $voters, $credits): Collection
    {
        $voters->each(function ($voter) use ($credits) {
            $voter->giveVoteCredits($credits);
        });

        return $voters;
    }
}