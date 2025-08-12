<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Fakultas;
use Illuminate\Support\Facades\Log;
use Exception;

class importFakultas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-fakultas';

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
        $this->info('Memulai import data Fakultas dari Feeder...');
        try {
            $client = app(FeederClient::class);
            $response = $client->fetchs('GetFakultas', '', '', 2000, 0); // Ambil semua data

            if (!$response || empty($response['data'])) {
                $this->warn('Tidak ada data Fakultas yang ditemukan di Feeder.');
                return 0;
            }

            $existingIds = Fakultas::pluck('feeder_id')->flip();
            $dataToInsert = collect($response['data'])->reject(function ($fakultas) use ($existingIds) {
                return isset($existingIds[$fakultas['id_fakultas']]);
            })->map(function ($fakultas) {
                return [
                    'feeder_id' => $fakultas['id_fakultas'],
                    'perguruan_tinggi_id' => 1, // Sesuaikan jika perlu
                    'nama' => $fakultas['nama_fakultas'],
                    'status' => $fakultas['status'],
                    'jenjang_pendidikan_id' => $fakultas['id_jenjang_pendidikan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->values()->toArray();

            if (!empty($dataToInsert)) {
                Fakultas::insert($dataToInsert);
                $this->info(count($dataToInsert) . ' data fakultas berhasil diimpor.');
            } else {
                $this->info('Tidak ada data fakultas baru yang perlu diimpor.');
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data fakultas: ' . $e->getMessage());
            Log::error('Gagal mengimpor data fakultas', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
