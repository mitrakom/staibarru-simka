<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Matakuliah;
use App\Models\Prodi;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Exception;

class importMatakuliah extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-matakuliah';

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
        $this->info('Memulai import data Mata Kuliah dari Feeder...');
        $batchSize = 100;
        $offset = 0;
        $totalSuccess = 0;
        $totalFailed = 0;
        $errorMessages = [];
        try {
            $client = app(FeederClient::class);
            $existingIds = Matakuliah::pluck('feeder_id')->flip();
            do {
                $response = $client->fetchs('GetListMataKuliah', '', '', $batchSize, $offset);
                if (!$response || empty($response['data'])) {
                    $this->info('Tidak ada data Mata Kuliah baru untuk diimpor pada batch offset ' . $offset);
                    break;
                }
                $dataToInsert = collect($response['data'])->reject(function ($matkul) use ($existingIds) {
                    return isset($existingIds[$matkul['id_matkul']]);
                })->map(function ($matkul) {
                    return [
                        'feeder_id' => $matkul['id_matkul'],
                        'prodi_id' => Prodi::where('feeder_id', $matkul['id_prodi'])->value('id'),
                        'kode' => $matkul['kode_mata_kuliah'],
                        'nama' => $matkul['nama_mata_kuliah'],
                        'sks' => floatval($matkul['sks_mata_kuliah'])  ?: 0,
                        'jenis_matakuliah_id' => $matkul['id_jenis_mata_kuliah'],
                        'kelompok_matakuliah_id' => $matkul['id_kelompok_mata_kuliah'],
                        'sks_tatap_muka' =>  $matkul['sks_tatap_muka'],
                        'sks_praktek' =>  $matkul['sks_praktek'],
                        'sks_praktek_lapangan' => $matkul['sks_praktek_lapangan'],
                        'sks_simulasi' =>  $matkul['sks_simulasi'],
                        'metode_kuliah' => $matkul['metode_kuliah'],
                        'ada_sap' => $matkul['ada_sap'],
                        'ada_silabus' => $matkul['ada_silabus'],
                        'ada_bahan_ajar' => $matkul['ada_bahan_ajar'],
                        'ada_acara_praktek' => $matkul['ada_acara_praktek'],
                        'ada_diktat' => $matkul['ada_diktat'],
                        'tanggal_mulai_efektif' => Carbon::parse($matkul['tanggal_mulai_efektif'])->format('Y-m-d'),
                        'tanggal_selesai_efektif' => Carbon::parse($matkul['tanggal_selesai_efektif'])->format('Y-m-d'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                })->values()->toArray();
                if (!empty($dataToInsert)) {
                    try {
                        Matakuliah::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                        $this->info(count($dataToInsert) . ' data matakuliah berhasil diimpor pada batch offset ' . $offset);
                    } catch (Exception $e) {
                        Log::error('Gagal mengimpor batch dari offset ' . $offset . ': ' . $e->getMessage());
                        $errorMessages[] = 'Gagal mengimpor batch dari offset ' . $offset;
                        $totalFailed += count($dataToInsert);
                    }
                }
                $offset += $batchSize;
            } while (count($response['data']) === $batchSize);
            $this->info("Import Mata Kuliah selesai: $totalSuccess sukses, $totalFailed gagal.");
            if ($totalFailed > 0) {
                $this->warn('Error: ' . implode(", ", $errorMessages));
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data matakuliah: ' . $e->getMessage());
            Log::error('Gagal mengimpor data matakuliah', ['exception' => $e]);
            return 1;
        }
        return 0;
    }
}
