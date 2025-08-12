<?php

namespace App\Services\Feeder\Import;

use App\Services\Feeder\FeederClient;
use App\Models\KurikulumMatakuliah;
use App\Models\ImportStatus;
use App\Models\Kurikulum;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Log;
use Exception;

class KurikulumMatakuliahImportService
{
    protected FeederClient $client;
    protected int $batchSize = 200; // Ukuran batch untuk query data secara bertahap

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Mata Kuliah dari Feeder PDDikti
     */
    public function import()
    {
        // KurikulumMatakuliah::truncate(); // Hapus semua data sebelum impor baru
        try {
            ImportStatus::updateStatus('kurikulumMatakuliah', 'in_progress');
            Log::info("Mulai impor data Mata Kuliah dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('GetMatkulKurikulum', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Mata Kuliah baru untuk diimpor.");
                    break;
                }

                // Siapkan data untuk diinsert
                $dataToInsert = $this->prepareData($response['data']);

                if (!empty($dataToInsert)) {
                    try {
                        KurikulumMatakuliah::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
                // Hentikan proses setelah batch pertama
                // break;
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus('kurikulumMatakuliah', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null, "Total sukses: $totalSuccess, Total gagal: $totalFailed");
            Log::info("Selesai impor data Mata Kuliah dari Feeder. Total sukses: $totalSuccess, total gagal: $totalFailed");
        } catch (Exception $e) {
            ImportStatus::updateStatus('kurikulumMatakuliah', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor data Kurikulum Mata Kuliah: " . $e->getMessage());
        }
    }

    /**
     * Siapkan data yang akan diinsert ke database
     *
     * @param array $data
     * @return array
     */
    private function prepareData(array $data)
    {
        return collect($data)->map(fn($item) => [
            'kurikulum_id' => Kurikulum::where('feeder_id', $item['id_kurikulum'])->value('id'), //$item['id_kurikulum']            
            'matakuliah_id' => Matakuliah::where('feeder_id', $item['id_matkul'])->value('id'), //$item['id_matkul']
            'semester_ke' => $item['semester'],
            'apakah_wajib' => $item['apakah_wajib'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();
    }
}
