<?php

namespace App\Services\Feeder\Import;

use App\Models\Kurikulum;
use App\Models\Prodi;
use App\Services\Feeder\FeederClient;
use Illuminate\Support\Facades\Log;
use Exception;
use Filament\Actions\Imports\Models\Import;
use App\Models\ImportStatus;

class KurikulumImportService
{
    protected FeederClient $client;
    protected int $batchSize = 200; // Ukuran batch untuk query data secara bertahap

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    public function import()
    {
        try {
            ImportStatus::updateStatus('kurikulum', 'in_progress');
            Log::info("Memulai import data kurikulum.");
            // Kurikulum::truncate(); // Hapus semua data sebelum impor baru

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            do {
                $response = $this->client->fetchs('GetListKurikulum', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data kurikulum baru untuk diimpor.");
                    break;
                }

                // Siapkan data untuk diinsert
                $dataToInsert = $this->prepareData($response['data']);

                if (!empty($dataToInsert)) {
                    try {
                        Kurikulum::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
            } while (count($response['data']) === $this->batchSize);

            // ImportStatus::updateStatus('kurikulum', 'done', $totalSuccess, $totalFailed, $errorMessages);
            ImportStatus::updateStatus('kurikulum', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null, "Total sukses: $totalSuccess, Total gagal: $totalFailed");

            Log::info("Selesai impor data kurikulum dari Feeder. Total sukses: $totalSuccess, total gagal: $totalFailed");
        } catch (Exception $e) {
            ImportStatus::updateStatus('kurikulum', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor data Kurikulum: " . $e->getMessage());
        }
    }

    private function prepareData(array $data)
    {
        return collect($data)->map(fn($item) => [
            'feeder_id' => $item['id_kurikulum'],
            'nama' => $item['nama_kurikulum'],
            'prodi_id' => Prodi::where('feeder_id', $item['id_prodi'])->value('id'),
            'semester' => $item['id_semester'],
            'keterangan' => $item['semester_mulai_berlaku'],
            'jumlah_sks_lulus' => $item['jumlah_sks_lulus'],
            'jumlah_sks_wajib' => $item['jumlah_sks_wajib'],
            'jumlah_sks_pilihan' => $item['jumlah_sks_pilihan'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();
    }
}
