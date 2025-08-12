<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Feeder\FeederClient;
use App\Models\Mahasiswa;
use App\Models\Prodi;
use Illuminate\Support\Facades\Log;
use Exception;

class importMahasiswa extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-mahasiswa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Mahasiswa data from Feeder by periode (tahun) and prodi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Input tahun
        $tahun = $this->ask('Masukkan tahun angkatan (4 digit)', date('Y'));
        // Ambil daftar prodi
        $prodis = Prodi::orderBy('nama')->get(['feeder_id', 'nama']);
        if ($prodis->isEmpty()) {
            $this->error('Tidak ada data prodi di database.');
            return 1;
        }
        $choices = $prodis->mapWithKeys(fn($p) => [$p->feeder_id => $p->nama])->toArray();
        $idProdiFeeder = $this->choice('Pilih Prodi', $choices);
        $this->info("Memulai import data Mahasiswa tahun $tahun prodi $idProdiFeeder dari Feeder...");
        $batchSize = 100;
        $offset = 0;
        $totalSuccess = 0;
        $totalFailed = 0;
        $errorMessages = [];
        try {
            $client = app(FeederClient::class);
            $prodiId = Prodi::where('feeder_id', $idProdiFeeder)->value('id');
            if (!$prodiId) {
                $this->error('Prodi dengan feeder_id ' . $idProdiFeeder . ' tidak ditemukan.');
                return 1;
            }
            $existingIds = Mahasiswa::pluck('feeder_id')->flip();
            do {
                $filter = "left(id_periode,4)='$tahun' and id_prodi='$idProdiFeeder'";
                $response = $client->fetchs('GetListMahasiswa', $filter, '', $batchSize, $offset);
                if (!$response || empty($response['data'])) {
                    $this->info('Tidak ada data Mahasiswa baru untuk diimpor pada batch offset ' . $offset);
                    break;
                }
                $dataToInsert = [];
                foreach ($response['data'] as $mhs) {
                    if (isset($existingIds[$mhs['id_registrasi_mahasiswa']])) {
                        continue;
                    }
                    // Get detail biodata
                    $idMhs = $mhs['id_mahasiswa'] ?? null;
                    $filters = "id_mahasiswa='$idMhs'";
                    $biodataResp = $client->fetchs('GetBiodataMahasiswa', $filters, '', 1, 0);
                    if (!$biodataResp || empty($biodataResp['data'][0])) {
                        $totalFailed++;
                        $errorMessages[] = 'Biodata tidak ditemukan untuk id_mahasiswa ' . $mhs['id_mahasiswa'];
                        continue;
                    }
                    $bio = $biodataResp['data'][0];
                    $dataToInsert[] = [
                        'prodi_id' => $prodiId,
                        'kurikulum_id' => null,
                        'agama' => $this->mapAgama($bio['nama_agama'] ?? null),
                        'nim' => $mhs['nim'] ?? '',
                        'nama' => $bio['nama_mahasiswa'] ?? '',
                        'angkatan' => intval(substr($mhs['id_periode'], 0, 4)),
                        'email' => $bio['email'] ?? '',
                        'tempat_lahir' => $bio['tempat_lahir'] ?? '',
                        'tanggal_lahir' => $this->parseTanggal($bio['tanggal_lahir'] ?? null),
                        'handphone' => $bio['handphone'] ?? '',
                        'alamat' => $bio['jalan'] ?? '',
                        'nisn' => $bio['nisn'] ?? '',
                        'nik' => $bio['nik'] ?? '',
                        'npwp' => $bio['npwp'] ?? '',
                        'jenis_kelamin' => $bio['jenis_kelamin'] ?? '*',
                        'feeder_id' => $mhs['id_registrasi_mahasiswa'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
                if (!empty($dataToInsert)) {
                    try {
                        Mahasiswa::insert($dataToInsert);
                        $totalSuccess += count($dataToInsert);
                        $this->info(count($dataToInsert) . ' data mahasiswa berhasil diimpor pada batch offset ' . $offset);
                    } catch (Exception $e) {
                        Log::error('Gagal mengimpor batch dari offset ' . $offset . ': ' . $e->getMessage());
                        $errorMessages[] = 'Gagal mengimpor batch dari offset ' . $offset;
                        $totalFailed += count($dataToInsert);
                    }
                }
                $offset += $batchSize;
            } while (count($response['data']) === $batchSize);
            $this->info("Import Mahasiswa selesai: $totalSuccess sukses, $totalFailed gagal.");
            if ($totalFailed > 0) {
                $this->warn('Error: ' . implode(", ", $errorMessages));
            }
        } catch (Exception $e) {
            $this->error('Gagal mengimpor data mahasiswa: ' . $e->getMessage());
            Log::error('Gagal mengimpor data mahasiswa', ['exception' => $e]);
            return 1;
        }
        return 0;
    }

    private function mapAgama($namaAgama)
    {
        // I = Islam, P = Protestan, K = Katolik, H = Hindu, B = Budha, C = Khonghucu
        return match (strtolower($namaAgama)) {
            'islam' => 'I',
            'protestan' => 'P',
            'katolik' => 'K',
            'hindu' => 'H',
            'budha' => 'B',
            'khonghucu' => 'C',
            default => null,
        };
    }

    private function parseTanggal($tgl)
    {
        if (!$tgl) return null;
        // Format bisa "28-02-2000" atau "2000-02-28"
        if (preg_match('/\d{2}-\d{2}-\d{4}/', $tgl)) {
            [$d, $m, $y] = explode('-', $tgl);
            return "$y-$m-$d";
        }
        return substr($tgl, 0, 10);
    }
}
