<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\PerguruanTinggi;
use Illuminate\Support\Facades\Log;
use Exception;

class importPt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-pt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Perguruan Tinggi (PT) data from Feeder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Memulai import data Perguruan Tinggi dari Feeder...');
        try {
            $client = app(FeederClient::class);
            $response = $client->fetch('GetProfilPT', [], '', 1, 0); // Hanya satu data PT

            if (!$response || empty($response['data'])) {
                $this->warn('Tidak ada data Perguruan Tinggi yang ditemukan di Feeder.');
                return 0;
            }

            $pt = $response['data'][0];

            $data = [
                'feeder_id' => $pt['id_perguruan_tinggi'],
                'kode' => $pt['kode_perguruan_tinggi'],
                'nama' => $pt['nama_perguruan_tinggi'],
                'telepon' => $pt['telepon'] ?? null,
                'faximile' => $pt['faximile'] ?? null,
                'email' => $pt['email'] ?? null,
                'website' => $pt['website'] ?? null,
                'jalan' => $pt['jalan'] ?? null,
                'dusun' => $pt['dusun'] ?? null,
                'rt_rw' => $pt['rt_rw'] ?? null,
                'kelurahan' => $pt['kelurahan'] ?? null,
                'kode_pos' => $pt['kode_pos'] ?? null,
                'id_wilayah' => $pt['id_wilayah'] ?? null,
                'nama_wilayah' => $pt['nama_wilayah'] ?? null,
                'lintang_bujur' => $pt['lintang_bujur'] ?? null,
                'bank' => $pt['bank'] ?? null,
                'unit_cabang' => $pt['unit_cabang'] ?? null,
                'nomor_rekening' => $pt['nomor_rekening'] ?? null,
                'mbs' => $pt['mbs'] ?? null,
                'luas_tanah_milik' => $pt['luas_tanah_milik'] ?? null,
                'luas_tanah_bukan_milik' => $pt['luas_tanah_bukan_milik'] ?? null,
                'sk_pendirian' => $pt['sk_pendirian'] ?? null,
                'tanggal_sk_pendirian' => isset($pt['tanggal_sk_pendirian']) ? substr($pt['tanggal_sk_pendirian'], 0, 10) : null,
                'id_status_milik' => $pt['id_status_milik'] ?? null,
                'nama_status_milik' => $pt['nama_status_milik'] ?? null,
                'status_perguruan_tinggi' => $pt['status_perguruan_tinggi'] ?? null,
                'sk_izin_operasional' => $pt['sk_izin_operasional'] ?? null,
                'tanggal_izin_operasional' => isset($pt['tanggal_izin_operasional']) ? substr($pt['tanggal_izin_operasional'], 0, 10) : null,
                'updated_at' => now(),
            ];

            // Upsert by feeder_id
            $ptModel = PerguruanTinggi::where('feeder_id', $data['feeder_id'])->first();
            if ($ptModel) {
                $ptModel->update($data);
                $this->info('Data Perguruan Tinggi berhasil diperbarui.');
            } else {
                $data['created_at'] = now();
                PerguruanTinggi::create($data);
                $this->info('Data Perguruan Tinggi berhasil diimpor.');
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data Perguruan Tinggi: ' . $e->getMessage());
            Log::error('Gagal mengimpor data Perguruan Tinggi', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
