<?php

namespace Database\Factories;

use App\Models\Animal;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Animal>
 */
class AnimalFactory extends Factory
{
    protected $model = Animal::class;

    public function definition(): array
    {
        return [
            'name'     => $this->faker->unique()->firstName,
            'species'  => $this->faker->randomElement(['dog','cat','rabbit']),
            'status'   => $this->faker->randomElement(['available','hold','pending','adopted']),
            'priority' => $this->faker->numberBetween(1, 5),
        ];
    }
}
