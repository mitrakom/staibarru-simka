<?php

namespace App\Services\Feeder;

use App\Services\Feeder\Import\PerguruanTinggiImportService;
use App\Services\Feeder\Import\FakultasImportService;
use App\Services\Feeder\Import\ProdiImportService;
use App\Services\Feeder\Import\KurikulumImportService;
use App\Services\Feeder\Import\MataKuliahImportService;
use App\Services\Feeder\Import\KurikulumMatakuliahImportService;

class ImportService
{
    protected PerguruanTinggiImportService $perguruanTinggiService;
    protected FakultasImportService $fakultasService;
    protected ProdiImportService $prodiService;
    protected KurikulumImportService $kurikulumService;
    protected MataKuliahImportService $mataKuliahService;
    protected KurikulumMatakuliahImportService $kurikulumMatakuliahService;

    public function __construct(
        PerguruanTinggiImportService $perguruanTinggiService,
        FakultasImportService $fakultasService,
        ProdiImportService $prodiService,
        KurikulumImportService $kurikulumService,
        MataKuliahImportService $mataKuliahService,
        KurikulumMatakuliahImportService $kurikulumMatakuliahService,
    ) {
        $this->perguruanTinggiService = $perguruanTinggiService;
        $this->fakultasService = $fakultasService;
        $this->prodiService = $prodiService;
        $this->kurikulumService = $kurikulumService;
        $this->mataKuliahService = $mataKuliahService;
        $this->kurikulumMatakuliahService = $kurikulumMatakuliahService;
    }

    /**
     * Jalankan proses import berdasarkan jenis data
     */
    public function import(string $type)
    {
        switch ($type) {
            case 'perguruan_tinggi':
                return $this->perguruanTinggiService->import();
            case 'fakultas':
                return $this->fakultasService->import();
            case 'prodi':
                return $this->prodiService->import();
            case 'kurikulum':
                return $this->kurikulumService->import();
            case 'mata_kuliah':
                return $this->mataKuliahService->import();
            case 'kurikulum_matakuliah':
                return $this->kurikulumMatakuliahService->import();
            default:
                throw new \Exception("Tipe import tidak dikenal: $type");
        }
    }
}
