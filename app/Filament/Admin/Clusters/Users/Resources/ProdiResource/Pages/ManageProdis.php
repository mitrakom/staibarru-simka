<?php

namespace App\Filament\Admin\Clusters\Users\Resources\ProdiResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\ProdiResource;
use App\Models\Prodi;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ManageProdis extends ManageRecords
{
    protected static string $resource = ProdiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(function (array $data): array {
                    $data['identity_type'] = Prodi::class;
                    return $data;
                })
                ->after(function (array $data, Model $record) {
                    $record->assignRole('prodi');
                })
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data berhasil disimpan')
                        ->icon('heroicon-o-check-circle')
                ),
        ];
    }
}
