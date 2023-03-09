# laravel-quadratic-voting
Quadratic Voting Implementation library to Laravel. 

Installation
```
composer require punksolid/laravel-quadratic-voting
```

```php
<?php 

 //User.php
 //Add the Voter trait 
 use LaravelQuadraticVoting\VoterTrait;
 
 
 class User extends Authenticatable
 {
     use VoterTrait;
 
```

```php
<?php

//On the models that are going to be votable add the following
use LaravelQuadraticVoting\isVotable;

//for example
class Idea extends Model
{
    use isVotable;


```
Basic Usage

To vote on something you just need to
```php
//get an isVotable Model
$idea = factory(Idea::class)->create();

//to the voter model, add an isVotable model, and in the second argument
//the number of the credits, it will proccess the credits to votes.
//For example if a voter puts, 9 vote credits to an isVotable model, it will count as 3 votes
$user->voteOn($idea, $vote_credits);
```

Methods available on voter
```php
      //ask if it has enough credits to spend
    $voter->hasCredits($wanna_spend) //boolean
    //add credits to a voter
    $voter->giveVoteCredits();
    
    //Return vote credits available
    $voter->getVoteCredits();
    
    //Give voters and assign equally/massively credits
    VoterModel::massiveVoteCredits($voter_collection, $credits);
    
    //You should not spend credits without voting, but in case you need
    //decrease the available credits to the user
    $voter->spendCredits($credits); //int
```

On the Votable model object is 
```php
//gets all the votes
$idea->getCountVotes()

//Return a collection of all the voters
$idea->getVoters();

```
