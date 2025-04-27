<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define default settings
        $settings = [
            'api_url' => 'https://api.example.com',
            'serial_check_enabled' => '1',
            'login_attempts' => '5',
            'strict_ip_check' => '1',
            'lockout_duration' => '60',
            'api_status' => '1',
        ];

        // Create or update each setting
        foreach ($settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
