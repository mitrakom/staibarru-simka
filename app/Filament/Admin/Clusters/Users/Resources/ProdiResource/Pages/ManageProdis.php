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
                ->modalDescription('Password default sementara adalah username. Silakan ganti password setelah login pertama.')
                ->mutateFormDataUsing(function (array $data): array {
                    $data['identity_type'] = Prodi::class;
                    $data['name'] = $data['username'];
                    $data['password'] = bcrypt($data['username']);

                    // Simpan role sebelum di-unset untuk digunakan di after()
                    $data['_temp_role'] = $data['role'];
                    unset($data['role']);

                    return $data;
                })
                ->after(function (array $data, Model $record) {
                    // Ambil role dari data yang sudah disimpan
                    $role = $data['_temp_role'] ?? 'prodi';

                    if ($role === 'admin') {
                        $record->assignRole('admin');
                    } else {
                        $record->assignRole('prodi');
                    }
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
