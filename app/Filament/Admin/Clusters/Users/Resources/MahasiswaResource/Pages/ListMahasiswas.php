<?php

namespace App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMahasiswas extends ListRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
