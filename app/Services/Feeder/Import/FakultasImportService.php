<?php

namespace App\Services\Feeder\Import;

use App\Services\Feeder\FeederClient;
use App\Models\Fakultas;
use App\Models\ImportStatus;
use Illuminate\Support\Facades\Log;
use Exception;

class FakultasImportService
{
    protected FeederClient $client;
    protected int $batchSize = 100; // Ukuran batch untuk query data secara bertahap

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Fakultas dari Feeder PDDikti
     */
    public function import()
    {
        try {
            ImportStatus::updateStatus('fakultas', 'in_progress');

            Log::info("Mulai impor data Fakultas dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            $existingIds = Fakultas::pluck('feeder_id')->flip();

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('GetFakultas', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Fakultas baru untuk diimpor.");
                    break;
                }

                $dataToInsert = $this->prepareData($response['data'], $existingIds);

                if (!empty($dataToInsert)) {
                    try {
                        Fakultas::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus(
                'fakultas',
                'done',
                $totalFailed > 0 ? implode(", ", $errorMessages) : null,
                "Total sukses: $totalSuccess, Total gagal: $totalFailed"
            );
            Log::info("Import Fakultas selesai: $totalSuccess sukses, $totalFailed gagal.");
        } catch (Exception $e) {
            ImportStatus::updateStatus('fakultas', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor Fakultas: " . $e->getMessage());
        }
    }

    /**
     * Persiapkan data sebelum dimasukkan ke dalam database
     */
    private function prepareData(array $data, $existingIds)
    {
        return collect($data)->reject(function ($fakultas) use ($existingIds) {
            return isset($existingIds[$fakultas['id_fakultas']]); // Skip jika feeder_id sudah ada
        })->map(function ($fakultas) {
            return [
                'feeder_id' => $fakultas['id_fakultas'],
                'perguruan_tinggi_id' => 1,
                'nama' => $fakultas['nama_fakultas'],
                'status' => $fakultas['status'],
                'jenjang_pendidikan_id' => $fakultas['id_jenjang_pendidikan'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->values()->toArray();
    }
}
