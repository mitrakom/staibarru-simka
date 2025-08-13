<?php

namespace App\Filament\Admin\Resources\ProdiResource\Pages;

use App\Filament\Admin\Resources\ProdiResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateProdi extends CreateRecord
{
    protected static string $resource = ProdiResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        $data['perguruan_tinggi_id'] = session('perguruan_tinggi_id');
        $data['fakultas_id'] = session('fakultas_id');

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
