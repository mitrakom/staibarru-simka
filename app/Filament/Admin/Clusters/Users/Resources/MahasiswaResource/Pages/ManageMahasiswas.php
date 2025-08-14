<?php

namespace App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\MahasiswaResource;
use App\Models\Mahasiswa;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;

class ManageMahasiswas extends ManageRecords
{
    protected static string $resource = MahasiswaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->modalDescription('Password default sementara adalah username. Silakan ganti password setelah login pertama.')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['identity_type'] = Mahasiswa::class;
                    $data['password'] = bcrypt($data['username']); // Set default password = username
                    return $data;
                })
                ->after(function (array $data, Model $record) {
                    $record->assignRole('mahasiswa');
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
