<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use LaravelQuadraticVoting\Models\Idea;

class IdeaFactory extends Factory
{
    protected $model = Idea::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(8),
        ];
    }
}
