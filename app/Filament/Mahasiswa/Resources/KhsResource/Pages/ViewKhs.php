<?php

namespace App\Filament\Mahasiswa\Resources\KhsResource\Pages;

use App\Filament\Mahasiswa\Resources\KhsResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewKhs extends ViewRecord
{
    protected static string $resource = KhsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
