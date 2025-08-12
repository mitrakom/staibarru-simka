<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources\RefAgamaResource\Pages;

use App\Filament\Admin\Clusters\Referensi\Resources\RefAgamaResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRefAgamas extends ManageRecords
{
    protected static string $resource = RefAgamaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
