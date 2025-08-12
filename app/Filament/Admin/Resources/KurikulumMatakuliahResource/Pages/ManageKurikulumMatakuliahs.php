<?php

namespace App\Filament\Admin\Resources\KurikulumMatakuliahResource\Pages;

use App\Filament\Admin\Resources\KurikulumMatakuliahResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKurikulumMatakuliahs extends ManageRecords
{
    protected static string $resource = KurikulumMatakuliahResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
