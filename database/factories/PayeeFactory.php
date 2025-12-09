<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PayeeFactory extends Factory
{
    public function definition()
    {
        return[
            'name'=> $this->faker->company(),
            'address'=>$this->faker->address(),
            'type'=>$this->faker->randomElement(['supplier', 'employee', 'government'])
        ];
    }
}
