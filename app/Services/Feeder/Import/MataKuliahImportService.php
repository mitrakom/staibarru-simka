<?php

namespace App\Services\Feeder\Import;

use App\Services\Feeder\FeederClient;
use App\Models\MataKuliah;
use App\Models\ImportStatus;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class MataKuliahImportService
{
    protected FeederClient $client;
    protected int $batchSize = 100; // Ukuran batch untuk query data secara bertahap

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Mata Kuliah dari Feeder PDDikti
     */
    public function import()
    {
        try {
            ImportStatus::updateStatus('matakuliah', 'in_progress');
            Log::info("Mulai impor data Mata Kuliah dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            $existingIds = MataKuliah::pluck('feeder_id')->flip();

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('GetListMataKuliah', '', '', $this->batchSize, $offset);

                // Log::info("response: " . json_encode($response));

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Mata Kuliah baru untuk diimpor.");
                    break;
                }

                $dataToInsert = $this->prepareData($response['data'], $existingIds);

                if (!empty($dataToInsert)) {
                    try {
                        MataKuliah::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
                // break; // Hentikan loop setelah satu batch untuk menghindari pengulangan yang tidak perlu
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus('matakuliah', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null, "Total sukses: $totalSuccess, Total gagal: $totalFailed");
            Log::info("Import Mata Kuliah selesai: $totalSuccess sukses, $totalFailed gagal.");
        } catch (Exception $e) {
            ImportStatus::updateStatus('matakuliah', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor Mata Kuliah: " . $e->getMessage());
        }
    }

    /**
     * Persiapkan data sebelum dimasukkan ke dalam database
     */
    private function prepareData(array $data, $existingIds)
    {
        return collect($data)->reject(function ($matkul) use ($existingIds) {
            return isset($existingIds[$matkul['id_matkul']]); // Skip jika feeder_id sudah ada
        })->map(function ($matkul) {
            return [
                'feeder_id' => $matkul['id_matkul'],
                'prodi_id' => Prodi::where('feeder_id', $matkul['id_prodi'])->value('id'),
                // 'kurikulum_id' => Kurikulum::where('feeder_id', $matkul['id_kurikulum'])->value('id'),
                'kode' => $matkul['kode_mata_kuliah'],
                'nama' => $matkul['nama_mata_kuliah'],
                'sks' => floatval($matkul['sks_mata_kuliah'])  ?: 0,
                'jenis_matakuliah_id' => $matkul['id_jenis_mata_kuliah'],
                'kelompok_matakuliah_id' => $matkul['id_kelompok_mata_kuliah'],
                'sks_tatap_muka' =>  $matkul['sks_tatap_muka'],
                'sks_praktek' =>  $matkul['sks_praktek'],
                'sks_praktek_lapangan' => $matkul['sks_praktek_lapangan'],
                'sks_simulasi' =>  $matkul['sks_simulasi'],
                'metode_kuliah' => $matkul['metode_kuliah'],
                'ada_sap' => $matkul['ada_sap'],
                'ada_silabus' => $matkul['ada_silabus'],
                'ada_bahan_ajar' => $matkul['ada_bahan_ajar'],
                'ada_acara_praktek' => $matkul['ada_acara_praktek'],
                'ada_diktat' => $matkul['ada_diktat'],
                'tanggal_mulai_efektif' => Carbon::parse($matkul['tanggal_mulai_efektif'])->format('Y-m-d'),
                'tanggal_selesai_efektif' => Carbon::parse($matkul['tanggal_selesai_efektif'])->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->values()->toArray();
    }
}
