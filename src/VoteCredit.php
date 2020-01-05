<?php

namespace Punksolid\LaravelQuadraticVoting;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoteCredit extends Model
{
    /** @var string */
    protected $table = 'vote_bag';

    /** @var array<string> */
    protected $fillable = [
      'credits',
    ];

    public function voter(): BelongsTo
    {
        return $this->belongsTo(VoterInterface::class, 'voter_id');
    }
}
