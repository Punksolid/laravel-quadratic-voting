<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelQuadraticVoting\Models\Politician;



class PoliticianFactory extends Factory
{
    protected $model = Politician::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'last_name' => $this->faker->lastName,
        ];
    }
}
