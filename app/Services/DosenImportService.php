<?php

namespace App\Services\Feeder;

use App\Models\Dosen;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DosenImportService
{
    protected FeederClient $client;

    public function __construct(FeederClient $client)
    {
        $this->client = $client;
    }

    /**
     * Mengambil data dosen dari Feeder dan menyimpannya ke database SIMKA
     */
    public function importDosen(string $filter = '', int $limit = 10, int $offset = 0)
    {
        $response = $this->client->fetch('DetailBiodataDosen', ['filter' => $filter], '', $limit, $offset);

        if (!$response || $response['error_code'] !== 0) {
            Log::error('Gagal mengambil data dosen dari Feeder', ['response' => $response]);
            return false;
        }

        foreach ($response['data'] as $dosenData) {
            try {
                Dosen::updateOrCreate(
                    ['feeder_uuid' => $dosenData['id_dosen']],
                    [
                        'nama_dosen' => $dosenData['nama_dosen'],
                        'tempat_lahir' => $dosenData['tempat_lahir'],
                        'tanggal_lahir' => $this->formatTanggal($dosenData['tanggal_lahir']),
                        'jenis_kelamin' => $dosenData['jenis_kelamin'],
                        'id_agama' => $dosenData['id_agama'],
                        'nama_agama' => $dosenData['nama_agama'],
                        'id_status_aktif' => $dosenData['id_status_aktif'],
                        'nama_status_aktif' => $dosenData['nama_status_aktif'],
                        'nidn' => $dosenData['nidn'],
                        'nama_ibu_kandung' => $dosenData['nama_ibu_kandung'],
                        'nik' => $dosenData['nik'],
                        'nip' => $dosenData['nip'],
                        'npwp' => $dosenData['npwp'],
                        'id_jenis_sdm' => $dosenData['id_jenis_sdm'],
                        'nama_jenis_sdm' => $dosenData['nama_jenis_sdm'],
                        'no_sk_cpns' => $dosenData['no_sk_cpns'],
                        'tanggal_sk_cpns' => $this->formatTanggal($dosenData['tanggal_sk_cpns']),
                        'no_sk_pengangkatan' => $dosenData['no_sk_pengangkatan'],
                        'mulai_sk_pengangkatan' => $this->formatTanggal($dosenData['mulai_sk_pengangkatan']),
                        'id_lembaga_pengangkatan' => $dosenData['id_lembaga_pengangkatan'],
                        'nama_lembaga_pengangkatan' => $dosenData['nama_lembaga_pengangkatan'],
                        'id_pangkat_golongan' => $dosenData['id_pangkat_golongan'],
                        'nama_pangkat_golongan' => $dosenData['nama_pangkat_golongan'],
                        'id_sumber_gaji' => $dosenData['id_sumber_gaji'],
                        'nama_sumber_gaji' => $dosenData['nama_sumber_gaji'],
                        'jalan' => $dosenData['jalan'],
                        'dusun' => $dosenData['dusun'],
                        'rt' => $dosenData['rt'],
                        'rw' => $dosenData['rw'],
                        'ds_kel' => $dosenData['ds_kel'],
                        'kode_pos' => $dosenData['kode_pos'],
                        'id_wilayah' => $dosenData['id_wilayah'],
                        'nama_wilayah' => $dosenData['nama_wilayah'],
                        'telepon' => $dosenData['telepon'],
                        'handphone' => $dosenData['handphone'],
                        'email' => $dosenData['email'],
                        'status_pernikahan' => $dosenData['status_pernikahan'],
                        'nama_suami_istri' => $dosenData['nama_suami_istri'],
                        'nip_suami_istri' => $dosenData['nip_suami_istri'],
                        'tanggal_mulai_pns' => $this->formatTanggal($dosenData['tanggal_mulai_pns']),
                        'id_pekerjaan_suami_istri' => $dosenData['id_pekerjaan_suami_istri'],
                        'nama_pekerjaan_suami_istri' => $dosenData['nama_pekerjaan_suami_istri'],

                        // Kolom Sinkronisasi Feeder
                        'feeder_status_sync' => 'sudah_sync',
                        'feeder_tanggal_sync' => Carbon::now(),
                        'feeder_last_update' => Carbon::now(),
                    ]
                );
            } catch (\Exception $e) {
                Log::error('Gagal menyimpan data dosen ke SIMKA', ['error' => $e->getMessage()]);
            }
        }

        return true;
    }

    /**
     * Mengonversi format tanggal dari Feeder ke format yang sesuai dengan database (Y-m-d)
     */
    private function formatTanggal($tanggal)
    {
        if (!$tanggal || $tanggal === '0000-00-00T00:00:00.000Z') {
            return null;
        }

        return Carbon::parse($tanggal)->format('Y-m-d');
    }
}
