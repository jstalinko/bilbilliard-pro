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
            'number' => $this->faker->unique()->numerify('Meja-##'),
            'rate_per_hour' => $this->faker->randomElement([30000, 40000, 50000]),
            'status' => 'available',
        ];
    }
}
