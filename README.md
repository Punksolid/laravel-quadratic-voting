# laravel-quadratic-voting
Quadratic Voting Implementation library to Laravel. 

What is Quadratic Voting?
It is a voting system designed to measure not only preference but also the intensity of that preference.
https://www.youtube.com/watch?v=pjbakxIvGFA

Installation
```
composer require punksolid/laravel-quadratic-voting
```

```php
<?php 

 //User.php
 //Add the Voter trait 
 use LaravelQuadraticVoting\Traits\VoterTrait;
 
 
 class User extends Authenticatable implements \LaravelQuadraticVoting\Interfaces\VoterInterface
 {
     use VoterTrait;
 
```

Currently you need to add the isVotable trait to your model and specify the voter model on the laravel_quadratic.php config file

```php
<?php

use LaravelQuadraticVoting\Traits\isVotable;

//for example
class Idea extends Model
{
    use isVotable;


```
Publish the `laravel_quadratic.php` config file
```
php artisan vendor:publish --provider="LaravelQuadraticVoting\LaravelQuadraticVotingServiceProvider"
```
Set the models on the config file. `models.voter` and `models.is_votable` may be the only models that you need to change.
```php
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
```

Basic Usage

To vote on something you just need to
```php
//get an isVotable Model
$idea = Idea::factory()->create();
$user->giveVoteCredits(14); //give credits to the voter
//to the voter model, add an isVotable model, and in the second argument
//the number of the credits, it will process the credits to votes.
$user->voteOn($idea, $vote_credits = 14); // This will set 3 votes to the idea 1 + 4 + 9 = 14

$user->downVote($idea); // This will set -1 vote to the idea and give you the credits back
```

Methods available on voter
```php
    //ask if it has enough credits to spend
    $voter->hasCredits($wanna_spend) //boolean
    
    //adds 100 credits to a voter
    $voter->giveVoteCredits(100);
    
    //Return vote credits available
    $voter->getVoteCredits();
    
    //Give voters and assign equally/massively credits
    VoterModel::massiveVoteCredits($voter_collection, $credits);
    VoterModel::massiveVoteReset($voter_collection); // All in 0 credits
    
    //You should not spend credits without voting, but in case you need
    //decrease the available credits to the user
    $voter->spendCredits($credits); //int

    // Get Next Vote Cost will return the credits to score 1 vote to the idea considering the previous votes of that user
    $voter->getNextVoteCost( $idea);
    
    // Get the real votes registered of a user in an specific idea
    $voter->getVotesAlreadyEmittedOnIdea($idea);
    
    // Get all the votes emitted by a voter in all the ideas
    $voter->getVotesAlreadyEmittedOverall();
    

```

On the Votable model object is 
```php
//gets all the votes
$idea->getCountVotes()

//Return a collection of all the voters
$idea->getVoters();

```


