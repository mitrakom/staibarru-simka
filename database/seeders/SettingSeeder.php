<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $settings = [
            ['key' => 'semester_aktif', 'value' => '20241', 'type' => 'integer', 'description' => 'Semester aktif saat ini'],
            ['key' => 'krs_online_semester', 'value' => '20241', 'type' => 'integer', 'description' => 'Semester aktif untuk krs online'],
            ['key' => 'krs_online_start_date', 'value' => '2025-03-01', 'type' => 'date', 'description' => 'Tanggal mulai krs online'],
            ['key' => 'krs_online_end_date', 'value' => '2025-03-05', 'type' => 'date', 'description' => 'Tanggal berakhir krs online'],
        ];

        foreach ($settings as $setting) {
            Setting::set($setting['key'], $setting['value'], $setting['type'], $setting['description']);
        }
    }
}
