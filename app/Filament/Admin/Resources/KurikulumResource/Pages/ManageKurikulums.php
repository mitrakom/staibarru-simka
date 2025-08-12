<?php

namespace App\Filament\Admin\Resources\KurikulumResource\Pages;

use App\Filament\Admin\Resources\KurikulumResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageKurikulums extends ManageRecords
{
    protected static string $resource = KurikulumResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->mutateFormDataUsing(
                    function (array $data): array {
                        $data['prodi_id'] = session('prodi_id');
                        return $data;
                    }
                ),
        ];
    }
}
