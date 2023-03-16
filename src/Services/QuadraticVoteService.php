<?php

namespace LaravelQuadraticVoting\Services;

use LaravelQuadraticVoting\Exceptions\NotExactCreditsForVotes;

class QuadraticVoteService
{
    private int $start_number_of_credits = 0;

    public function setStartNumberOfVotes(int $start_number_of_votes = 0): self
    {
        $this->start_number_of_credits = $this->convertVotesToCredits($start_number_of_votes);

        return $this;
    }

    /**
     * @param int $credits
     * @return int
     * @throws NotExactCreditsForVotes
     */
    public function processCreditsToVotes(int $credits): int
    {
        if ($credits < 0) {
            throw new NotExactCreditsForVotes('Credits should not be negative');
        }
        $credits = $credits + $this->start_number_of_credits;

        $votes = 0;
        $credits_array = $this->convertCreditsInArrayOfCreditsInQuadraticOrder($credits);

        foreach ($credits_array as $credit) {
            $this->processOneVote($credit);
            $votes++;
        }

        return $votes;
    }

    /**
     * @param $credits
     * @return int
     * @throws NotExactCreditsForVotes
     */
    public function processOneVote($credits): int
    {
        if (sqrt($credits) != floor(sqrt($credits))) {
            throw new NotExactCreditsForVotes();
        }

        return sqrt($credits);
    }

    /**
     * @param int $credits
     * @return array
     * @throws NotExactCreditsForVotes
     */
    public function convertCreditsInArrayOfCreditsInQuadraticOrder(int $credits): array
    {
        $credits_array = [];
        $vote_cost_representation = 1;
        while ($credits > 0) {
            $credits_array[] = $vote_cost_representation * $vote_cost_representation;

            $credits -= $vote_cost_representation * $vote_cost_representation;
            if ($credits < 0) {
                throw new NotExactCreditsForVotes();
            }
            $vote_cost_representation++;
        }

        return $credits_array;
    }

    public function convertVotesToCredits(int $votes): int
    {
        $credits = 0;
        for ($i = 1; $i <= $votes; $i++) {
            $credits += $i * $i;
        }

        return $credits;
    }

    public function getCostOfVoteNumber(int $vote): int
    {
        return $vote * $vote;
    }


}
