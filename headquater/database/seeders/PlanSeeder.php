<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create the default "Free Tier" plan
        Plan::create([
            'name' => 'Free Tier',
            'slug' => 'free',
            'limit_checkouts' => 300,
            'limit_fraud_ips' => 100,
            'limit_fraud_emails' => 100,
            'limit_fraud_phones' => 100,
            'limit_courier_checks' => 3000
        ]);
    }
}
