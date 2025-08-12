<?php

namespace App\Filament\Admin\Resources\ImportStatusResource\Pages;

use App\Filament\Admin\Resources\ImportStatusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditImportStatus extends EditRecord
{
    protected static string $resource = ImportStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
