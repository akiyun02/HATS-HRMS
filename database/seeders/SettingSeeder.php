<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['key' => 'attendance.start_time', 'value' => '09:00', 'group' => 'attendance'],
            ['key' => 'attendance.end_time', 'value' => '17:00', 'group' => 'attendance'],
            ['key' => 'company.name', 'value' => 'HATS HRMS Portal', 'group' => 'general'],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }
    }
}
