<?php

namespace App\Filament\Admin\Clusters\Users\Resources\DosenResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\DosenResource;
use App\Models\Dosen;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageDosens extends ManageRecords
{
    protected static string $resource = DosenResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalDescription('Password default sementara adalah username. Silakan ganti password setelah login pertama.')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['identity_type'] = Dosen::class;
                    $data['password'] = bcrypt($data['username']); // Set default password = username
                    return $data;
                })
                ->after(function (array $data, Model $record) {
                    $record->assignRole('dosen');
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
