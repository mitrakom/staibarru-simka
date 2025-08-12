<?php

namespace App\Filament\Admin\Resources\PerguruanTinggiResource\Pages;

use App\Filament\Admin\Resources\PerguruanTinggiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPerguruanTinggi extends EditRecord
{
    protected static string $resource = PerguruanTinggiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
