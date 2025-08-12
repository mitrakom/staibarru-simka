<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ImportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'perguruanTinggi',
                'description' => 'Import data perguruan tinggi',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'fakultas',
                'description' => 'Import data fakultas',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'prodi',
                'description' => 'Import data prodi',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'dosen',
                'description' => 'Import data dosen',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'kurikulum',
                'description' => 'Import data kurikulum',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'matakuliah',
                'description' => 'Import data mata kuliah',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'kurikulumMatakuliah',
                'description' => 'Import data Kurikulum Mata Kuliah',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'description' => 'Import data KRS',
                'name' => 'krs',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'description' => 'Import data Kelas',
                'name' => 'kelas',
                'status' => 'pending',
                'error_summary' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],


        ];

        DB::table('import_statuses')->insert($data);
    }
}
