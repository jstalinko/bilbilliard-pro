<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BilliardTable>
 */
class BilliardTableFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement(['RASSON-'.rand(1,9) , 'AILEEX-'.rand(1,9),'PREDATOR-'.rand(1,9)]),
            'number' => $this->faker->unique()->numerify('##'),
            'status' => $this->faker->randomElement(['available','maintenance']),
        ];
    }
}
