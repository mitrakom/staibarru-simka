<?php

namespace App\Filament\Admin\Resources\ImportStatusResource\Pages;

use App\Filament\Admin\Resources\ImportStatusResource;
use App\Http\Controllers\ImportController;
use App\Services\Feeder\FeederClient;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListImportStatuses extends ListRecords
{
    protected static string $resource = ImportStatusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('Import Semua')
                // ->icon('heroicon-o-cloud-upload')
                ->action(function () {
                    $feederClient = app(FeederClient::class);
                    $controller = new ImportController($feederClient);
                    $controller->importData();
                    Notification::make()
                        ->title('Data berhasil diimpor')
                        ->success()
                        ->send();
                })
        ];
    }
}
