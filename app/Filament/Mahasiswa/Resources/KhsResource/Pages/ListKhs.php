<?php

namespace App\Filament\Mahasiswa\Resources\KhsResource\Pages;

use App\Filament\Mahasiswa\Resources\KhsResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKhs extends ListRecords
{
    protected static string $resource = KhsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
