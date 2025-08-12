<?php

namespace App\Filament\Admin\Resources\PenasehatAkademikResource\Pages;

use App\Filament\Admin\Resources\PenasehatAkademikResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPenasehatAkademiks extends ListRecords
{
    protected static string $resource = PenasehatAkademikResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
