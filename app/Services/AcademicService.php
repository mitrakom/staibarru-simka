<?php

namespace App\Services;

use App\Models\Setting;

class AcademicService
{
    /**
     * Konversi kode semester ke format akademik
     * Contoh: 20241 → Ganjil 2024/2025
     */
    public function formatSemester($semester): string
    {
        if (!$semester) {
            return 'Tidak ada semester';
        }

        $year = substr($semester, 0, 4);
        $term = substr($semester, -1);

        $semesterNames = [
            '1' => "Ganjil",
            '2' => "Genap",
            '3' => "Pendek",
        ];

        $nextYear = $year + 1;
        $academicYear = "{$year}/{$nextYear}";

        return ($semesterNames[$term] ?? 'Unknown') . " {$academicYear}";
    }

    /**
     * Mendapatkan semester aktif untuk KRS Online
     * data diambil dari tabel setting "krs_online_semester"
     */
    public function semesterAktifKrsOnline(): string
    {
        $semester = Setting::get('krs_online_semester', 'Tidak Diketahui');
        return $this->formatSemester($semester);
    }

    /**
     * Mengecek apakah semester tertentu adalah semester ganjil atau genap
     */
    public function isOddSemester($semester): bool
    {
        return substr($semester, -1) === '1';
    }

    public function isEvenSemester($semester): bool
    {
        return substr($semester, -1) === '2';
    }
}
