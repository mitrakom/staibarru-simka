<?php

namespace App\Filament\Admin\Resources\MahasiswaResource\Pages;

use App\Filament\Admin\Resources\MahasiswaResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateMahasiswa extends CreateRecord
{
    protected static string $resource = MahasiswaResource::class;

    protected function getRedirectUrl(): string
    {
        return MahasiswaResource::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('Kembali')
                ->url(MahasiswaResource::getUrl('index'))
                ->icon('heroicon-o-arrow-left')
        ];
    }


    // mutate form
    // protected function mutateFormData(array $data): array
    // {
    //     $data['prodi_id'] = session('prodi_id');

    //     return $data;
    // }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['prodi_id'] = session('prodi_id');
        return $data;
    }
}
