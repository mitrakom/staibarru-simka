<?php

namespace App\Services\Feeder\Import;

use App\Services\Feeder\FeederClient;
use App\Models\PerguruanTinggi;
use App\Models\ImportStatus;
use Illuminate\Support\Facades\Log;
use Exception;

class PerguruanTinggiImportService
{
    protected FeederClient $client;
    protected int $batchSize = 100; // Ukuran batch untuk impor

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Perguruan Tinggi dari Feeder PDDikti
     */
    public function import()
    {
        try {
            ImportStatus::updateStatus('perguruan_tinggi', 'in_progress');
            Log::info("Mulai impor data Perguruan Tinggi dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            $existingIds = PerguruanTinggi::pluck('feeder_id')->flip();

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('GetProfilPT', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Perguruan Tinggi baru untuk diimpor.");
                    break;
                }

                $dataToInsert = $this->prepareData($response['data'], $existingIds);

                if (!empty($dataToInsert)) {
                    try {
                        PerguruanTinggi::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus('perguruan_tinggi', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null);
            Log::info("Import Perguruan Tinggi selesai: $totalSuccess sukses, $totalFailed gagal.");
        } catch (Exception $e) {
            ImportStatus::updateStatus('perguruan_tinggi', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor Perguruan Tinggi: " . $e->getMessage());
        }
    }

    /**
     * Persiapkan data sebelum dimasukkan ke dalam database
     */
    private function prepareData(array $data, $existingIds)
    {
        return collect($data)->reject(function ($pt) use ($existingIds) {
            return isset($existingIds[$pt['id_perguruan_tinggi']]); // Skip jika feeder_id sudah ada
        })->map(function ($pt) {
            return [
                'feeder_id' => $pt['id_perguruan_tinggi'],
                'kode_pt' => $pt['kode_perguruan_tinggi'],
                'nama' => $pt['nama_perguruan_tinggi'],
                'status' => $pt['status_perguruan_tinggi'],
                'alamat' => $pt['alamat'],
                'telepon' => $pt['telepon'],
                'email' => $pt['email'],
                'website' => $pt['website'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->values()->toArray();
    }
}
