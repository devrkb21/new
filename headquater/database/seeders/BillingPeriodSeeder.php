<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\BillingPeriod;

class BillingPeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // In database/seeders/BillingPeriodSeeder.php
public function run(): void
{
    BillingPeriod::create([
        'name' => 'Monthly',
        'slug' => 'monthly',
        'duration_in_days' => 30
    ]);

    BillingPeriod::create([
        'name' => 'Yearly',
        'slug' => 'yearly',
        'duration_in_days' => 365
    ]);
    BillingPeriod::create([
        'name' => 'Lifetime',
        'slug' => 'lifetime',
        'duration_in_days' => 0
    ]);
}
}
