<?php

namespace Database\Seeders;

use App\Models\Dosen;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\RefSemester;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $semester = 20241;

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'prodi']);
        Role::create(['name' => 'dosen']);
        Role::create(['name' => 'mahasiswa']);

        $this->call([
            SettingSeeder::class,
            // ImportSeeder::class,
            ProdiSeeder::class,
        ]);

        $admin = User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'identity_id' => 1,
            'identity_type' => Prodi::class, // Simpan tipe modelnya
            'semester' => $semester,
            'prodi_id' => 1,
        ]);
        $admin->assignRole('admin');

        $prodi = User::factory()->create([
            'name' => 'Prodi User',
            'email' => 'prodi@example.com',
            'identity_id' => 1,
            'identity_type' => Prodi::class,
            'semester' => $semester,
            'prodi_id' => 1,
        ]);
        $prodi->assignRole('prodi');

        $dosen = User::factory()->create([
            'name' => 'Dosen User',
            'email' => 'dosen@example.com',
            'identity_id' => 1,
            'identity_type' => Dosen::class,
            'semester' => $semester,
            'prodi_id' => 1,
        ]);
        $dosen->assignRole('dosen');


        $mahasiswa = User::factory()->create([
            'name' => 'Mahasiswa User',
            'email' => 'mahasiswa@example.com',
            'identity_id' => 1,
            'identity_type' => Mahasiswa::class,
            'semester' => $semester,
            'prodi_id' => 1,
        ]);
        $mahasiswa->assignRole('mahasiswa');

        // 20201 => Ganjil 2020/2021, 20202 => Genap 2020/2021, 20203 => Pendek 2020/2021
        $startYear = 2020;
        $endYear = 2025;
        $semesters = ['Ganjil', 'Genap', 'Pendek'];
        for ($year = $startYear; $year <= $endYear; $year++) {
            for ($i = 1; $i <= 3; $i++) {
                $key = $year . $i;
                $value = $semesters[$i - 1] . " $year/" . ($year + 1);
                RefSemester::create([
                    'key' => $key,
                    'value' => $value
                ]);
            }
        }
    }
}
