<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\FundSource>
 */
class FundSourceFactory extends Factory
{
    public function definition()
    {
        return [
            'code' => $this->faker->unique()->bothify('FUND-###'),
            'name' => $this->faker->words(3, true),
            'initial_balance' => $this->faker->randomFloat(2, 10000, 1000000),
            'description' => $this->faker->sentence(),
            'is_active' => true,            
        ];
    }
}
