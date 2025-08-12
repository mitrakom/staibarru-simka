<?php

namespace App\Filament\Mahasiswa\Resources\TnsResource\Pages;

use App\Filament\Mahasiswa\Resources\TnsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTns extends EditRecord
{
    protected static string $resource = TnsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
