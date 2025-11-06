<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name'     => fake()->unique()->firstName(),
            'species'  => fake()->randomElement(['dog','cat','rabbit']),
            'status'   => fake()->randomElement(['available','hold','pending','adopted']),
            'priority' => fake()->numberBetween(1, 5),
        ];
    }
}
