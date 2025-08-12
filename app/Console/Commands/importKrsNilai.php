<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Krs;
use App\Models\Nilai;
use Illuminate\Support\Facades\Log;
use Exception;

class importKrsNilai extends Command
{
    protected $signature = 'app:import-krs-nilai';
    protected $description = 'Import KRS dan Nilai Mahasiswa dari Feeder';

    public function handle()
    {
        // Input tahun
        $tahun = $this->ask('Masukkan tahun angkatan (4 digit)', date('Y'));
        // Ambil daftar prodi
        $prodis = Prodi::orderBy('nama')->get(['feeder_id', 'nama']);
        if ($prodis->isEmpty()) {
            $this->error('Tidak ada data prodi di database.');
            return 1;
        }
        $choices = $prodis->mapWithKeys(fn($p) => [$p->feeder_id => $p->nama])->toArray();
        $idProdiFeeder = $this->choice('Pilih Prodi', $choices);
        $prodiId = Prodi::where('feeder_id', $idProdiFeeder)->value('id');
        if (!$prodiId) {
            $this->error('Prodi dengan feeder_id ' . $idProdiFeeder . ' tidak ditemukan.');
            return 1;
        }

        $this->info("Mengambil data mahasiswa tahun $tahun prodi $idProdiFeeder...");
        $mahasiswas = Mahasiswa::where('prodi_id', $prodiId)
            ->where('angkatan', $tahun)
            ->get();
        if ($mahasiswas->isEmpty()) {
            $this->warn('Tidak ada mahasiswa ditemukan untuk prodi dan tahun tersebut.');
            return 0;
        }

        $client = app(FeederClient::class);
        $totalKrs = 0;
        $totalNilai = 0;
        $totalError = 0;
        foreach ($mahasiswas as $mhs) {
            $filter = "id_registrasi_mahasiswa='" . $mhs->feeder_id . "'";
            try {
                $response = $client->fetchs('GetDetailNilaiPerkuliahanKelas', $filter, '', 0, 0);
                if (!$response || empty($response['data'])) {
                    $this->warn('Tidak ada data KRS/Nilai untuk mahasiswa NIM ' . $mhs->nim);
                    continue;
                }
                foreach ($response['data'] as $item) {
                    // Cari matakuliah_id
                    $matkulId = Matakuliah::where('feeder_id', $item['id_matkul'])->value('id');
                    if (!$matkulId) {
                        $this->warn('Matakuliah tidak ditemukan: ' . $item['nama_mata_kuliah'] . ' (feeder_id: ' . $item['id_matkul'] . ')');
                        $totalError++;
                        continue;
                    }
                    // Jika nilai_huruf null, abaikan input nilai
                    if (!isset($item['nilai_huruf']) || is_null($item['nilai_huruf'])) {
                        continue;
                    }
                    // Semester dari id_semester (ambil 5 digit, misal 20202)
                    $semester = intval($item['id_semester']);
                    // Upsert KRS
                    $krs = Krs::firstOrCreate([
                        'mahasiswa_id' => $mhs->id,
                        'matakuliah_id' => $matkulId,
                        'semester' => $semester,
                    ], [
                        'verifikasi' => 'y',
                    ]);
                    $totalKrs++;
                    // Upsert Nilai
                    $nilaiData = [
                        'nilai' => $item['nilai_angka'] ?? '',
                        'huruf' => $item['nilai_huruf'] ?? '',
                        'index' => $item['nilai_indeks'] ?? '',
                        'bobot' => $item['nilai_indeks'] ?? '',
                        'mutu' => $item['nilai_angka'] ?? '',
                        'is_transfer' => 't',
                    ];
                    Nilai::updateOrCreate([
                        'krs_id' => $krs->id,
                    ], $nilaiData);
                    $totalNilai++;
                }
            } catch (Exception $e) {
                $this->error('Gagal import KRS/Nilai untuk NIM ' . $mhs->nim . ': ' . $e->getMessage());
                Log::error('Gagal import KRS/Nilai', ['nim' => $mhs->nim, 'exception' => $e]);
                $totalError++;
            }
        }
        $this->info("Import selesai. Total KRS: $totalKrs, Total Nilai: $totalNilai, Error: $totalError");
        return 0;
    }
}
