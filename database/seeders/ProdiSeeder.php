<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProdiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('perguruan_tinggis')->insert([
            'feeder_id' => Str::uuid(),
            'nama' => 'Universitas Contoh',
            'jalan' => 'Jl. Contoh No. 1, Kota Contoh',
            'telepon' => '021-12345678',
            'email' => 'info@universitascontoh.ac.id',
            'website' => 'https://www.universitascontoh.ac.id',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('fakultas')->insert([
            [
                'feeder_id' => Str::uuid(),
                'nama' => 'Fakultas Teknik',
                'perguruan_tinggi_id' => 1, // Asumsikan Universitas Contoh memiliki ID 1
                'status' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('prodis')->insert([
            [
                'feeder_id' => Str::uuid(),
                'kode' => 'TI',
                'nama' => 'Teknik Informatika',
                'fakultas_id' => 1, // Asumsikan Fakultas Teknik memiliki ID 1
                'perguruan_tinggi_id' => 1,
                'jenjang_pendidikan_id' => 1, // Asumsikan jenjang_id 1 adalah S1
                'telepon' => '021-12345681',
                'kaprodi_nama' => 'Kaprodi TI',
                'kaprodi_nidn' => 'NIDN0001',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
