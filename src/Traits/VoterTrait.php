<?php
/**
 * Created by PhpStorm.
 * User: ps
 * Date: 27/10/18
 * Time: 12:03 AM
 */

namespace LaravelQuadraticVoting\Traits;


use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use LaravelQuadraticVoting\Exceptions\NotExactCreditsForVotes;
use LaravelQuadraticVoting\Interfaces\IsVotableInterface;
use LaravelQuadraticVoting\Models\User;
use LaravelQuadraticVoting\Models\VoteCredit;
use LaravelQuadraticVoting\Services\QuadraticVoteService;

trait VoterTrait
{
    public static function massiveVoteReset(Model|\Illuminate\Database\Eloquent\Collection $users): void
    {
        if ($users instanceof Model) {
            $users = collect([$users]);
        }

        $users->each(function (User $user) {
            $user->voteCredits()->delete();
        });
    }

    /**
     * Registers a vote on a votable model and returns the final number of votes registered
     *
     * @param IsVotableInterface $model
     * @param int $vote_credits
     * @return int
     * @throws NotExactCreditsForVotes
     */
    public function voteOn(Model $model, int $vote_credits = 1): int
    {
        if (!$this->hasCredits($vote_credits)) {
            throw new Exception("Voter has not enough credits");
        }

        $votes_already_emitted = $this->getVotesAlreadyEmittedOnIdea($model);
        $this->ideas()->detach([$model->id]);
        $quadraticVoteService = new QuadraticVoteService();
        $quadraticVoteService->setStartNumberOfVotes($votes_already_emitted);
        $votes_quantity = $quadraticVoteService->processCreditsToVotes($vote_credits);

        $this->ideas()->attach($model->id, [
            config('laravel_quadratic.column_names.voter_key') => $this->id,
            "votable_type" => get_class($model),
            "votable_id" => $model->id,
            "quantity" => $votes_quantity
        ]);

        $this->spendCredits($vote_credits);

        return $votes_quantity;

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

    /**
     * Relationship definition with ideas
     * @return BelongsToMany
     */
    public function ideas(): BelongsToMany
    {
        return $this->belongsToMany(
            config('laravel_quadratic.models.is_votable'), // Idea::class,
            "votes",
            config('laravel_quadratic.column_names.voter_key'), // "voter_id",
            "votable_id")
            ->withPivot([
                "votable_type",
                "votable_id",
                "quantity"
            ])->withTimestamps();
    }

    /**
     * Relationship definition with vote credits
     * @return HasOne
     */
    public function voteCredits(): HasOne
    {
        return $this->hasOne(config('laravel_quadratic.models.vote_credit'), config('laravel_quadratic.column_names.voter_key'));
    }

    public function giveVoteCredits(int $vote_credits = 1): VoteCredit
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

    /**
     * Get the number of credits that the voter has available to use
     */
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

    public function getVotesAlreadyEmittedOverall(): int
    {
        return $this->ideas()->sum('quantity');
    }

    public function getVotesAlreadyEmittedOnIdea(Model $is_votable_model): int
    {

        $tablename_keyname_id = $is_votable_model->getTable() . '.' . $is_votable_model->getKeyName();

        return $this->ideas()
            ->where('votable_id', $is_votable_model->id)
            ->where('votable_type', get_class($is_votable_model))
            ->groupBy(
                config('laravel_quadratic.column_names.voter_key'),
                $tablename_keyname_id,
            )->get()
            ->sum('pivot.quantity');
    }

    public function getNextVoteCost(Model $idea): int
    {
        $votes_already_emitted = $this->getVotesAlreadyEmittedOnIdea($idea);
        $quadraticVoteService = new QuadraticVoteService();

        return $quadraticVoteService->convertVotesToCredits($votes_already_emitted + 1) - $quadraticVoteService->convertVotesToCredits($votes_already_emitted);
    }
}
