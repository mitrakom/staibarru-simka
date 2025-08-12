<?php

namespace App\Filament\Admin\Clusters\Users\Resources\AdminResource\Pages;

use App\Filament\Admin\Clusters\Users\Resources\AdminResource;
use App\Models\UserRole;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ManageAdmins extends ManageRecords
{
    protected static string $resource = AdminResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['roles'] = ['admin'];
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['roles'] = ['admin'];
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $user = parent::handleRecordCreation($data);
        $user->assignRole('admin');
        // Tambahkan data ke tabel user_roles
        UserRole::create([
            'user_id' => $user->id,
            'prodi_id' => $data['prodi_id'],
        ]);
        return $user;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $user = parent::handleRecordUpdate($record, $data);
        $user->syncRoles(['admin']);

        // Perbarui data di tabel user_roles
        $userRole = UserRole::where('user_id', $user->id)->first();
        if ($userRole) {
            $userRole->update([
                'prodi_id' => $data['prodi_id'],
            ]);
        } else {
            UserRole::create([
                'user_id' => $user->id,
                'prodi_id' => $data['prodi_id'],
            ]);
        }

        return $user;
    }
}
