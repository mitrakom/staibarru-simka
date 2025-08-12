<?php

namespace App\Services\Feeder\Import;

use App\Services\Feeder\FeederClient;
use App\Models\Prodi;
use App\Models\ImportStatus;
use Illuminate\Support\Facades\Log;
use Exception;

class ProdiImportService
{
    protected FeederClient $client;
    protected int $batchSize = 100; // Ukuran batch data yang diambil per permintaan

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Program Studi dari Feeder PDDikti
     */
    public function import()
    {
        try {
            ImportStatus::updateStatus('prodi', 'in_progress');
            Log::info("Mulai impor data Program Studi dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            $existingIds = Prodi::pluck('feeder_id')->flip();

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('GetProdi', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Program Studi baru untuk diimpor.");
                    break;
                }

                $dataToInsert = $this->prepareData($response['data'], $existingIds);

                if (!empty($dataToInsert)) {
                    try {
                        Prodi::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus('prodi', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null, "Total sukses: $totalSuccess, Total gagal: $totalFailed");
            Log::info("Import Program Studi selesai: $totalSuccess sukses, $totalFailed gagal.");
        } catch (Exception $e) {
            ImportStatus::updateStatus('prodi', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor Program Studi: " . $e->getMessage());
        }
    }

    /**
     * Persiapkan data sebelum dimasukkan ke dalam database
     */
    private function prepareData(array $data, $existingIds)
    {
        return collect($data)->reject(function ($prodi) use ($existingIds) {
            return isset($existingIds[$prodi['id_prodi']]); // Skip jika feeder_id sudah ada
        })->map(function ($prodi) {
            return [
                'perguruan_tinggi_id' => 1, // Disesuaikan dengan ID PT yang sesuai
                // 'fakultas_id' => 1, // belum ditentukan / null
                'feeder_id' => $prodi['id_prodi'],
                'kode' => $prodi['kode_program_studi'],
                'nama' => $prodi['nama_program_studi'],
                'status' => $prodi['status'],
                'jenjang_pendidikan_id' => $prodi['id_jenjang_pendidikan'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->values()->toArray();
    }
}
