<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 27/10/18
 * Time: 12:03 AM
 */

namespace LaravelQuadraticVoting\Traits;


use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use LaravelQuadraticVoting\Interfaces\IsVotableInterface;
use LaravelQuadraticVoting\Models\Idea;
use LaravelQuadraticVoting\Models\VoteCredit;

trait VoterTrait
{
    public function voteOn(IsVotableInterface $model, int $vote_credits = 1): int|bool
    {
        if (!$this->hasCredits($vote_credits)) {
            return false;
        }

        $votes_already_emitted = $this->getVotesAlreadyEmitted();
        $this->ideas()->detach();
        $votes_credits_already_emitted = $votes_already_emitted ** 2;
        $total_vote_credits = $vote_credits + $votes_credits_already_emitted;
        $votes_quantity = sqrt($total_vote_credits); //new

        $this->ideas()->attach($model->id, [
            config('laravel_quadratic.column_names.voter_key') => $this->id,
            "votable_type" => get_class($model),
            "votable_id" => $model->id,
            "quantity" => $votes_quantity
        ]);

        $this->spendCredits($vote_credits);

        return $vote_credits;

    }

    public function hasCredits(int $wanna_spend): bool
    {
        $vote_bag = $this->voteCredits()->first();
        return $vote_bag && $vote_bag->credits >= $wanna_spend;
    }

    public function spendCredits(int $credits): int
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
        return $this->belongsToMany(Idea::class, "votes", config('laravel_quadratic.column_names.voter_key'), "votable_id")
            ->withPivot([
                "votable_type",
                "votable_id",
                "quantity"
            ])->withTimestamps();
    }

    public function voteCredits(): HasOne
    {
        return $this->hasOne(VoteCredit::class, config('laravel_quadratic.column_names.voter_key'));
    }

    public function giveVoteCredits(int $vote_credits = 1): VoteCredit
    {

        return $this->voteCredits()->firstOrCreate([], ['credits' => 0])
            ->tap(function ($vote_bag) use ($vote_credits) {
                $vote_bag->update(['credits' => $vote_bag->credits + $vote_credits]);
            });

//        return $vote_bag;
    }

    public function getVoteCredits(): int
    {
        return (int)optional($this->voteCredits()->first())->credits;
    }

    static function massiveVoteCredits(Collection $voters, int $credits): Collection
    {
        $voters->each(function ($voter) use ($credits) {
            $voter->giveVoteCredits($credits);
        });

        return $voters;
    }

    public function getVotesAlreadyEmitted(): int
    {
        return $this->ideas()
            ->groupBy(
                config('laravel_quadratic.column_names.voter_key')
            )->get()
            ->sum('credits');
    }
}
