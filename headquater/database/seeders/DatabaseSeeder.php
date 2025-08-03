<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // In database/seeders/DatabaseSeeder.php -> run() method
        $this->call([
            PlanSeeder::class,
            AdminUserSeeder::class,
            BillingPeriodSeeder::class,
            CustomPlanSeeder::class // Add this line
        ]);
    }
}