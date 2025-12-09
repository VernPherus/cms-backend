<?php

namespace Database\Seeders;

use App\Models\FundSource;
use App\Models\Payee;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create 3 Specific Fund Sources (Realistic Data)
        FundSource::factory()
            ->count(3)
            ->state(new \Illuminate\Database\Eloquent\Factories\Sequence(
                ['code' => 'GF-101', 'name' => 'General Fund', 'description' => 'For general operational expenses'],
                ['code' => 'SEF-200', 'name' => 'Special Education Fund', 'description' => 'School board expenses'],
                ['code' => 'TF-300', 'name' => 'Trust Fund', 'description' => 'Held in trust for specific purposes'],
            ))
            ->create();

        // Create 3 Random Payees
        Payee::factory()->count(3)->create();
        
        // Optional: Create one specifically named Payee for testing
        Payee::factory()->create([
            'name' => 'Meralco',
            'type' => 'supplier',
        ]);
    }
}
