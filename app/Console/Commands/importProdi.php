<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Prodi;
use Illuminate\Support\Facades\Log;
use Exception;

class importProdi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-prodi';

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
        $this->info('Memulai import data Program Studi dari Feeder...');
        try {
            $client = app(FeederClient::class);
            $response = $client->fetchs('GetProdi', '', '', 2000, 0); // Ambil semua data

            if (!$response || empty($response['data'])) {
                $this->warn('Tidak ada data Program Studi yang ditemukan di Feeder.');
                return 0;
            }

            $existingIds = Prodi::pluck('feeder_id')->flip();
            $dataToInsert = collect($response['data'])->reject(function ($prodi) use ($existingIds) {
                return isset($existingIds[$prodi['id_prodi']]);
            })->map(function ($prodi) {
                return [
                    'perguruan_tinggi_id' => 1, // Sesuaikan jika perlu
                    'feeder_id' => $prodi['id_prodi'],
                    'kode' => $prodi['kode_program_studi'],
                    'nama' => $prodi['nama_program_studi'],
                    'status' => $prodi['status'],
                    'jenjang_pendidikan_id' => $prodi['id_jenjang_pendidikan'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->values()->toArray();

            if (!empty($dataToInsert)) {
                Prodi::insert($dataToInsert);
                $this->info(count($dataToInsert) . ' data prodi berhasil diimpor.');
            } else {
                $this->info('Tidak ada data prodi baru yang perlu diimpor.');
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data prodi: ' . $e->getMessage());
            Log::error('Gagal mengimpor data prodi', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
