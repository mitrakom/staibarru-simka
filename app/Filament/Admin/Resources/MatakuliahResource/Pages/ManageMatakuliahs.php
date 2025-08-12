<?php

namespace App\Filament\Admin\Resources\MatakuliahResource\Pages;

use App\Filament\Admin\Resources\MatakuliahResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageMatakuliahs extends ManageRecords
{
    protected static string $resource = MatakuliahResource::class;

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
