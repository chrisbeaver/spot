<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metals = ['gold', 'silver', 'platinum'];
        $metal = fake()->randomElement($metals);
        
        return [
            'user_id' => User::factory(),
            'type' => fake()->randomElement(['buy', 'sell']),
            'description' => fake()->sentence(),
            'metal' => $metal,
            'weight' => fake()->randomFloat(2, 0.1, 100),
            'weight_unit' => fake()->randomElement(['oz', 'gram']),
        ];
    }
}
