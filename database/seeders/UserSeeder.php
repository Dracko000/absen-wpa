<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Super Admin
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Create Admin
        User::factory()->create([
            'name' => 'Admin Teacher',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Student
        User::factory()->create([
            'name' => 'John Doe',
            'email' => 'student@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'class_id' => 1, // Assign to first class
            'email_verified_at' => now(),
        ]);

        // Create additional sample users for testing
        User::factory(5)->create([
            'role' => 'user',
            'class_id' => 1, // Assign to first class
        ]);

        User::factory(2)->create([
            'role' => 'admin',
        ]);

        // Additional Super Admin for testing (optional)
        User::factory()->create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@example.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Michael Smith',
            'email' => 'michael@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        User::factory()->create([
            'name' => 'Emma Wilson',
            'email' => 'emma@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'class_id' => 2, // Assign to second class
            'email_verified_at' => now(),
        ]);
    }
}