<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\KurikulumMatakuliah;
use App\Models\Kurikulum;
use App\Models\Matakuliah;
use Illuminate\Support\Facades\Log;
use Exception;

class importKurikulumMatakuliah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-kurikulum-matakuliah';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai import data Kurikulum-Matakuliah dari Feeder...');
        $batchSize = 200;
        $offset = 0;
        $totalSuccess = 0;
        $totalFailed = 0;
        $errorMessages = [];
        try {
            $client = app(FeederClient::class);
            do {
                $response = $client->fetchs('GetMatkulKurikulum', '', '', $batchSize, $offset);
                if (!$response || empty($response['data'])) {
                    $this->info('Tidak ada data Kurikulum-Matakuliah baru untuk diimpor pada batch offset ' . $offset);
                    break;
                }
                $dataToInsert = collect($response['data'])->map(function ($item) {
                    return [
                        'kurikulum_id' => Kurikulum::where('feeder_id', $item['id_kurikulum'])->value('id'),
                        'matakuliah_id' => Matakuliah::where('feeder_id', $item['id_matkul'])->value('id'),
                        'semester_ke' => $item['semester'],
                        'apakah_wajib' => $item['apakah_wajib'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->filter(function ($row) {
                    // Hanya insert jika kurikulum_id dan matakuliah_id valid
                    return $row['kurikulum_id'] && $row['matakuliah_id'];
                })->values()->toArray();
                if (!empty($dataToInsert)) {
                    try {
                        KurikulumMatakuliah::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                        $this->info(count($dataToInsert) . ' data kurikulum-matakuliah berhasil diimpor pada batch offset ' . $offset);
                    } catch (Exception $e) {
                        Log::error('Gagal mengimpor batch dari offset ' . $offset . ': ' . $e->getMessage());
                        $errorMessages[] = 'Gagal mengimpor batch dari offset ' . $offset;
                        $totalFailed += count($dataToInsert);
                    }
                }
                $offset += $batchSize;
            } while (count($response['data']) === $batchSize);
            $this->info("Import Kurikulum-Matakuliah selesai: $totalSuccess sukses, $totalFailed gagal.");
            if ($totalFailed > 0) {
                $this->warn('Error: ' . implode(", ", $errorMessages));
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data kurikulum-matakuliah: ' . $e->getMessage());
            Log::error('Gagal mengimpor data kurikulum-matakuliah', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
