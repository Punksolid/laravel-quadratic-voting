<?php

namespace LaravelQuadraticVoting\Services;

use LaravelQuadraticVoting\Exceptions\NotExactCreditsForVotes;

class QuadraticVoteService
{

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
        $i = 1;
        while ($credits > 0) {
            $credits_array[] = $i * $i;
            $credits -= $i * $i;
            // credits cannot be negative
            if ($credits < 0) {
                throw new NotExactCreditsForVotes();
            }
            $i++;
        }

        return $credits_array;
    }
}
