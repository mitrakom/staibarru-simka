<?php

namespace App\Filament\Mahasiswa\Resources\KrsResource\Pages;

use App\Filament\Mahasiswa\Resources\KrsResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListKrs extends ListRecords
{
    protected static string $resource = KrsResource::class;

    protected function getHeaderActions(): array
    {
        $isKrsOnlineActive = Setting::isKrsOnlineActive();
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('add-krs')
                ->label('Tambah KRS')
                ->icon('heroicon-o-clipboard-document-check')
                ->disabled(!$isKrsOnlineActive)
                ->url($this->getResource()::getUrl('add')),
        ];
    }
}
