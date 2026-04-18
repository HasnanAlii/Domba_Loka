<?php

namespace Database\Factories;

use App\Models\Sheep;
use App\Models\SheepType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sheep>
 */
class SheepFactory extends Factory
{
    protected $model = Sheep::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type_id' => SheepType::factory(),
            'price' => fake()->randomFloat(2, 1000000, 5000000),
            'weight' => fake()->randomFloat(2, 20, 80),
            'condition' => fake()->randomElement(['Sangat Baik', 'Baik', 'Cukup']),
            'code' => 'SHP-' . fake()->unique()->numberBetween(1000, 9999),
            'status' => fake()->randomElement(['tersedia', 'terjual', 'booking']),
        ];
    }
}
