<?php

namespace App\Filament\Admin\Resources\KelasResource\Pages;

use App\Filament\Admin\Resources\KelasResource;
use App\Models\Matakuliah;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms;
use Filament\Forms\Components\Builder;

class ListKelas extends ListRecords
{
    protected static string $resource = KelasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\CreateAction::make(),
            Actions\Action::make('back')
                ->label('Tambah Kelas')
                ->icon('heroicon-o-plus')
                ->url(KelasResource::getUrl('rekap')),


            // berikut saya ingin membuat tombol untuk buat form tambah kelas dengan modal


            // Actions\Action::make('Tambah Kelas')
            //     // ->action('create')
            //     ->modalHeading('Tambah Kelas')
            //     ->modalWidth('lg')
            //     ->form([
            //         Forms\Components\TextInput::make('nama')
            //             ->required()
            //             ->label('Nama Kelas')
            //             ->placeholder('Masukkan Nama Kelas'),
            //         Forms\Components\Select::make('matakuliah_id')
            //             ->options(
            //                 Matakuliah::where('prodi_id', session('prodi_id'))
            //                     ->pluck('nama', 'id')
            //             )
            //             ->searchable()
            //             ->preload()
            //             ->required()
            //     ])
            //     ->icon('heroicon-o-plus')
            //     ->color('primary'),

        ];
    }
}
