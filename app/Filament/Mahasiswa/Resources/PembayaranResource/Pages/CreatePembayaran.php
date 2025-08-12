<?php

namespace App\Filament\Mahasiswa\Resources\PembayaranResource\Pages;

use App\Filament\Mahasiswa\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreatePembayaran extends CreateRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['mahasiswa_id'] = Auth::user()->identity->id;
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
