<?php

namespace App\Filament\Mahasiswa\Resources\TnsResource\Pages;

use App\Filament\Mahasiswa\Resources\TnsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTns extends ListRecords
{
    protected static string $resource = TnsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
