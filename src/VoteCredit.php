<?php

namespace Punksolid\LaravelQuadraticVoting;

//use Illuminate\Database\Eloquent\Relations\Pivot;

use Illuminate\Database\Eloquent\Model;

class VoteCredit extends Model
{
    protected $table = 'vote_bag';

    protected $fillable = [
      'credits'
    ];

    public function voter()
    {
        //@Todo change User::class for the real voter class
        return $this->belongsTo(User::class, 'voter_id');
    }
}
