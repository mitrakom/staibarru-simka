<?php

namespace App\Filament\Admin\Clusters\Referensi\Resources\RefSemesterResource\Pages;

use App\Filament\Admin\Clusters\Referensi\Resources\RefSemesterResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageRefSemesters extends ManageRecords
{
    protected static string $resource = RefSemesterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
