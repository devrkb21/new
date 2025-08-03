<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class CustomPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * This seeder creates a special, non-public plan that acts as a placeholder
     * for sites that have fully custom pricing and limits.
     */
    public function run(): void
    {
        Plan::updateOrCreate(
            ['slug' => 'custom'], // Find the plan by its unique slug
            [
                'name' => 'Custom',
                'is_public' => false, // Ensure it's not shown on public pricing pages
                'limit_checkouts' => 0,
                'limit_fraud_ips' => 0,
                'limit_fraud_emails' => 0,
                'limit_fraud_phones' => 0,
                'limit_courier_checks' => 0,
            ]
        );
    }
}