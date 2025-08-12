<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Kurikulum;
use App\Models\Prodi;
use Illuminate\Support\Facades\Log;
use Exception;

class importKurikulum extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-kurikulum';

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
        $this->info('Memulai import data Kurikulum dari Feeder...');
        try {
            $client = app(FeederClient::class);
            $response = $client->fetchs('GetListKurikulum', '', '', 2000, 0); // Ambil semua data

            if (!$response || empty($response['data'])) {
                $this->warn('Tidak ada data Kurikulum yang ditemukan di Feeder.');
                return 0;
            }

            $existingIds = Kurikulum::pluck('feeder_id')->flip();
            $dataToInsert = collect($response['data'])->reject(function ($item) use ($existingIds) {
                return isset($existingIds[$item['id_kurikulum']]);
            })->map(function ($item) {
                return [
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
                ];
            })->values()->toArray();

            if (!empty($dataToInsert)) {
                Kurikulum::insert($dataToInsert);
                $this->info(count($dataToInsert) . ' data kurikulum berhasil diimpor.');
            } else {
                $this->info('Tidak ada data kurikulum baru yang perlu diimpor.');
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data kurikulum: ' . $e->getMessage());
            Log::error('Gagal mengimpor data kurikulum', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
