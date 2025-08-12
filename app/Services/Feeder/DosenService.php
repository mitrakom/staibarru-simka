<?php

namespace App\Services\Feeder;

use App\Models\Dosen;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DosenService
{
    protected FeederClient $client;

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    public function import(int $limit = 10, int $offset = 0)
    {
        $existingDosenUuids = Dosen::pluck('feeder_uuid')->toArray();
        $filter = empty($existingDosenUuids) ? '' : "id_dosen NOT IN ('" . implode("','", $existingDosenUuids) . "')";

        $response = $this->client->fetch('DetailBiodataDosen', ['filter' => $filter], '', $limit, $offset);

        if (!$response || $response['error_code'] !== 0) {
            Log::error('Gagal mengambil data dosen dari Feeder', ['response' => $response]);
            return false;
        }

        DB::beginTransaction();
        try {
            foreach ($response['data'] as $item) {
                Dosen::updateOrCreate(
                    ['feeder_uuid' => $item['id_dosen']],
                    [
                        'nama_dosen' => $item['nama_dosen'],
                        'tempat_lahir' => $item['tempat_lahir'],
                        'tanggal_lahir' => $item['tanggal_lahir'],
                        'jenis_kelamin' => $item['jenis_kelamin'],
                        'id_agama' => $item['id_agama'],
                        'nama_agama' => $item['nama_agama'],
                        'id_status_aktif' => $item['id_status_aktif'],
                        'nama_status_aktif' => $item['nama_status_aktif'],
                        'nidn' => $item['nidn'],
                        'nama_ibu_kandung' => $item['nama_ibu_kandung'],
                        'nik' => $item['nik'],
                        'nip' => $item['nip'],
                        'npwp' => $item['npwp'],
                        'id_jenis_sdm' => $item['id_jenis_sdm'],
                        'nama_jenis_sdm' => $item['nama_jenis_sdm'],
                        'feeder_status_sync' => 'sudah_sync',
                        'feeder_tanggal_sync' => now(),
                        'feeder_last_update' => now()
                    ]
                );
            }
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal menyimpan data dosen ke SIMKA', ['error' => $e->getMessage()]);
            return false;
        }
    }
}
