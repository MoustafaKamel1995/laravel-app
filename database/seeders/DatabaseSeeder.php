<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin123'),
            'is_active' => true,
            'expiry_date' => Carbon::now()->addYears(10), // Set expiry date far in the future
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Run the settings seeder
        $this->call(SettingsSeeder::class);

        // You can uncomment this if you want to create test users
        // \App\Models\User::factory(10)->create();
    }
}
