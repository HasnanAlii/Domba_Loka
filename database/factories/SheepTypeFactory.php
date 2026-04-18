<?php

namespace Database\Factories;

use App\Models\SheepType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SheepType>
 */
class SheepTypeFactory extends Factory
{
    protected $model = SheepType::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement(['Garut', 'Texel', 'Merino', 'Suffolk', 'Dorset', 'Barbados Blackbelly', 'Dorper']),
            'description' => fake()->sentence(),
        ];
    }
}
