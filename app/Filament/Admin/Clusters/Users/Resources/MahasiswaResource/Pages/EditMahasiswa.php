<?php

namespace App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMahasiswa extends EditRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
