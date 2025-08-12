<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources\RefJenjangResource\Pages;

use App\Filament\Admin\Clusters\Referensi\Resources\RefJenjangResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRefJenjangs extends ManageRecords
{
    protected static string $resource = RefJenjangResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
