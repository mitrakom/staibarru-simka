<?php

namespace App\Filament\Mahasiswa\Resources\PembayaranResource\Pages;

use App\Filament\Mahasiswa\Resources\PembayaranResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPembayaran extends ViewRecord
{
    protected static string $resource = PembayaranResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
