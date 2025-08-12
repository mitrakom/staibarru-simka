<?php

namespace App\Filament\Admin\Resources\DosenResource\Pages;

use App\Filament\Admin\Resources\DosenResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateDosen extends CreateRecord
{
    protected static string $resource = DosenResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // $data['prodi_id'] = session('prodi_id');
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
