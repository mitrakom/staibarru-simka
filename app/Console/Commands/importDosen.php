<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class importDosen extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-dosen';

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
        $this->info('Memulai import data Dosen dari Feeder...');
        try {
            $client = app(FeederClient::class);
            $response = $client->fetchs('DetailBiodataDosen', '', '', 2000, 0); // Ambil semua data

            if (!$response || empty($response['data'])) {
                $this->warn('Tidak ada data Dosen yang ditemukan di Feeder.');
                return 0;
            }

            $existingIds = Dosen::pluck('feeder_id')->flip();
            $dataToInsert = collect($response['data'])->reject(function ($item) use ($existingIds) {
                return isset($existingIds[$item['id_dosen']]);
            })->map(function ($item) {
                return [
                    'feeder_id' => $item['id_dosen'],
                    'nama' => $item['nama_dosen'],
                    'tempat_lahir' => $item['tempat_lahir'],
                    'tanggal_lahir' => Carbon::parse($item['tanggal_lahir'])->format('Y-m-d'),
                    'jenis_kelamin' => $item['jenis_kelamin'],
                    'agama_id' => $item['id_agama'],
                    'status_aktif_id' => $item['id_status_aktif'],
                    'nidn' => $item['nidn'],
                    'nama_ibu_kandung' => $item['nama_ibu_kandung'],
                    'nik' => $item['nik'],
                    'nip' => $item['nip'],
                    'npwp' => $item['npwp'],
                    'jenis_sdm_id' => $item['id_jenis_sdm'],
                    'no_sk_cpns' => $item['no_sk_cpns'],
                    'tanggal_sk_cpns' => Carbon::parse($item['tanggal_sk_cpns'])->format('Y-m-d'),
                    'no_sk_pengangkatan' => $item['no_sk_pengangkatan'],
                    'mulai_sk_pengangkatan' => Carbon::parse($item['mulai_sk_pengangkatan'])->format('Y-m-d'),
                    'lembaga_pengangkatan_id' => $item['id_lembaga_pengangkatan'],
                    'pangkat_golongan_id' => $item['id_pangkat_golongan'],
                    'sumber_gaji_id' => $item['id_sumber_gaji'],
                    'jalan' => $item['jalan'],
                    'dusun' => $item['dusun'],
                    'rt' => $item['rt'],
                    'rw' => $item['rw'],
                    'ds_kel' => $item['ds_kel'],
                    'kode_pos' => $item['kode_pos'],
                    'wilayah_id' => $item['id_wilayah'],
                    'telepon' => $item['telepon'],
                    'handphone' => $item['handphone'],
                    'email' => $item['email'],
                    'status_pernikahan' => $item['status_pernikahan'],
                    'nama_suami_istri' => $item['nama_suami_istri'],
                    'nip_suami_istri' => $item['nip_suami_istri'],
                    'tanggal_mulai_pns' => Carbon::parse($item['tanggal_mulai_pns'])->format('Y-m-d'),
                    'pekerjaan_suami_istri_id' => $item['id_pekerjaan_suami_istri'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->values()->toArray();

            if (!empty($dataToInsert)) {
                Dosen::insert($dataToInsert);
                $this->info(count($dataToInsert) . ' data dosen berhasil diimpor.');
            } else {
                $this->info('Tidak ada data dosen baru yang perlu diimpor.');
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data dosen: ' . $e->getMessage());
            Log::error('Gagal mengimpor data dosen', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
