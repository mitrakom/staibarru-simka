<?php

namespace App\Filament\Mahasiswa\Resources\KhsResource\Pages;

use App\Filament\Mahasiswa\Resources\KhsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditKhs extends EditRecord
{
    protected static string $resource = KhsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
