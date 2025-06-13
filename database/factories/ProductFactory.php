<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Teh Botol',
                'Kopi',
                'Indomie',
                'Air Mineral',
                'Mie Goreng',
                'Nasi Goreng',
                'Coklat Dingin',
                'Es Jeruk',
                'Kacang',
                'Kerupuk'
            ]),
            'category' => $this->faker->randomElement(['Minuman', 'Makanan', 'Snack']),
            'price' => $this->faker->numberBetween(5000, 20000),
            'stock' => 100,
        ];
    }
}
