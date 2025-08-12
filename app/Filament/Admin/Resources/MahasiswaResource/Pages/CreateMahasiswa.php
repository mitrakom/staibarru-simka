<?php

namespace App\Filament\Admin\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Resources\MahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMahasiswa extends CreateRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['prodi_id'] = session('prodi_id');
        return $data;
    }
}
