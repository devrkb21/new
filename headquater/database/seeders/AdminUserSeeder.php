<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'email' => 'smru2020@gmail.com',
            'password' => Hash::make('R@KIb2121'), // Change 'password' to a secure default
            'is_admin' => true,
            'phone' => '01885660190',
        ]);
    }
}