<?php

namespace App\Services\Feeder\Import;

use App\Models\Dosen;
use App\Services\Feeder\FeederClient;
use App\Models\Fakultas;
use App\Models\ImportStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class DosenImportService
{
    protected FeederClient $client;
    protected int $batchSize = 100; // Ukuran batch untuk query data secara bertahap

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Jalankan proses impor data Dosen dari Feeder PDDikti
     */
    public function import()
    {
        try {
            ImportStatus::updateStatus('dosen', 'in_progress');
            Log::info("Mulai impor data Dosen dari Feeder.");

            $offset = 0;
            $totalSuccess = 0;
            $totalFailed = 0;
            $errorMessages = [];

            $existingIds = Dosen::pluck('feeder_id')->flip();

            do {
                // Ambil data dari Feeder
                $response = $this->client->fetchs('DetailBiodataDosen', '', '', $this->batchSize, $offset);

                if (!$response || empty($response['data'])) {
                    Log::info("Tidak ada data Dosen baru untuk diimpor.");
                    break;
                }

                $dataToInsert = $this->prepareData($response['data'], $existingIds);

                if (!empty($dataToInsert)) {
                    try {
                        Dosen::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                    } catch (Exception $e) {
                        Log::error("Gagal mengimpor batch dari offset $offset: " . $e->getMessage());
                        $errorMessages[] = "Gagal mengimpor batch dari offset $offset.";
                        $totalFailed += count($dataToInsert);
                    }
                }

                $offset += $this->batchSize;
                // break; // Hentikan loop setelah satu batch untuk pengujian
            } while (count($response['data']) === $this->batchSize);

            ImportStatus::updateStatus('dosen', 'done', $totalFailed > 0 ? implode(", ", $errorMessages) : null, "Total sukses: $totalSuccess, Total gagal: $totalFailed");
            Log::info("Import Dosen selesai: $totalSuccess sukses, $totalFailed gagal.");
        } catch (Exception $e) {
            ImportStatus::updateStatus('dosen', 'failed', $e->getMessage());
            Log::error("Gagal mengimpor Dosen: " . $e->getMessage());
        }
    }




    /**
     * Persiapkan data untuk diinsert ke database
     */
    protected function prepareData(array $data, $existingIds)
    {
        return collect($data)->reject(function ($item) use ($existingIds) {
            return isset($existingIds[$item['id_dosen']]); // Skip jika feeder_id sudah ada
        })->map(function ($item) {
            return [
                'feeder_id' => $item['id_dosen'],
                'nama' => $item['nama_dosen'],
                'tempat_lahir' => $item['tempat_lahir'],
                'tanggal_lahir' => Carbon::parse($item['tanggal_lahir'])->format('Y-m-d'),
                'jenis_kelamin' => $item['jenis_kelamin'],
                'agama_id' => $item['id_agama'],
                // 'nama_agama' => $item['nama_agama'],
                'status_aktif_id' => $item['id_status_aktif'],
                // 'nama_status_aktif' => $item['nama_status_aktif'],
                'nidn' => $item['nidn'],
                // 'nuptk' => $item['nuptk'],
                'nama_ibu_kandung' => $item['nama_ibu_kandung'],
                'nik' => $item['nik'],
                'nip' => $item['nip'],
                'npwp' => $item['npwp'],
                'jenis_sdm_id' => $item['id_jenis_sdm'],
                // 'nama_jenis_sdm' => $item['nama_jenis_sdm'],
                'no_sk_cpns' => $item['no_sk_cpns'],
                'tanggal_sk_cpns' => Carbon::parse($item['tanggal_sk_cpns'])->format('Y-m-d'),
                'no_sk_pengangkatan' => $item['no_sk_pengangkatan'],
                'mulai_sk_pengangkatan' => Carbon::parse($item['mulai_sk_pengangkatan'])->format('Y-m-d'),
                'lembaga_pengangkatan_id' => $item['id_lembaga_pengangkatan'],
                // 'nama_lembaga_pengangkatan' => $item['nama_lembaga_pengangkatan'],
                'pangkat_golongan_id' => $item['id_pangkat_golongan'],
                // 'nama_pangkat_golongan' => $item['nama_pangkat_golongan'],
                'sumber_gaji_id' => $item['id_sumber_gaji'],
                // 'nama_sumber_gaji' => $item['nama_sumber_gaji'],
                'jalan' => $item['jalan'],
                'dusun' => $item['dusun'],
                'rt' => $item['rt'],
                'rw' => $item['rw'],
                'ds_kel' => $item['ds_kel'],
                'kode_pos' => $item['kode_pos'],
                'wilayah_id' => $item['id_wilayah'],
                // 'nama_wilayah' => $item['nama_wilayah'],
                'telepon' => $item['telepon'],
                'handphone' => $item['handphone'],
                'email' => $item['email'],
                'status_pernikahan' => $item['status_pernikahan'],
                'nama_suami_istri' => $item['nama_suami_istri'],
                'nip_suami_istri' => $item['nip_suami_istri'],
                'tanggal_mulai_pns' => Carbon::parse($item['tanggal_mulai_pns'])->format('Y-m-d'),
                'pekerjaan_suami_istri_id' => $item['id_pekerjaan_suami_istri'],
                // 'nama_pekerjaan_suami_istri' => $item['nama_pekerjaan_suami_istri'],

                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->values()->toArray();
    }
}
