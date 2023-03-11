<?php

return [
    'models' => [
        'voter' => Illuminate\Foundation\Auth\User::class, //App\Models\User::class,
        'is_votable' => \LaravelQuadraticVoting\Models\Idea::class,
        'vote_credit' => LaravelQuadraticVoting\Models\VoteCredit::class,
    ],

    'table_names' => [
        'votes' => 'votes',
        'vote_credits' => 'vote_bag',
    ],

    'column_names' => [
        'voter_key' => 'voter_id',
    ]
];
