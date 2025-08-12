<?php

namespace App\Filament\Admin\Resources\ProdiResource\Pages;

use App\Filament\Admin\Resources\ProdiResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProdi extends EditRecord
{
    protected static string $resource = ProdiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }


    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
